function GenericViewModel(entityName, columns) {
    var self = this;

    self.items = ko.observableArray([]);
    self.selectedItems = ko.observableArray([]);

    self.searchQuery = ko.observable('');
    self.currentPage = ko.observable(1);
    self.totalPages = ko.observable(0);
    self.limit = ko.observable(10);
    self.sortingColumn = ko.observable(columns[0].key);
    self.sortingOrder = ko.observable('asc');

    // Store filters passed as parameters
    self.filters = ko.observable(filters);


    self.getSortingIcon = function (column) {
        if (self.sortingColumn() === column) {
            return self.sortingOrder() === 'asc' ? '<i class="fas fa-arrow-up text-gray-400 ml-2"></i>' : '<i class="fas fa-arrow-down text-gray-400 ml-2"></i>';
        }
        return '';
    };

    // Fetch items from the server
    self.fetchItems = function () {
        var queryParams = {
            search: self.searchQuery(),
            limit: self.limit(),
            page: self.currentPage(),
            sort: self.sortingColumn(),
            order: self.sortingOrder(),
            // Dynamically include filters
            ...self.filters() // Spread the filters into the query params
        };

        // Build the query string dynamically
        var queryString = Object.keys(queryParams)
            .filter(key => queryParams[key]) // Only include non-empty values
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(queryParams[key]))
            .join('&');

        fetch(`/api/${entityName}?${queryString}`)
            .then(response => response.json())
            .then(data => {
                self.items(data.data);
                self.totalPages(data.last_page);
                if (self.currentPage() > self.totalPages()) {
                    self.currentPage(self.totalPages());
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    };


    // Delete an item
    self.deleteItem = function (item) {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch(`/api/${entityName}/${item.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token if needed
                }
            })
                .then(response => {
                    if (response.ok) {
                        // Remove the item from the observable array
                        self.items.remove(item);
                        console.log('Item deleted successfully');
                    } else {
                        console.error('Error deleting item:', response.statusText);
                    }
                })
                .catch(error => console.error('Error deleting item:', error));
        }
    };

    // Function to delete selected items
    self.deleteItems = function (item) {
        const aa = self.selectedItems();
        debugger;
        if (self.selectedItems().length === 0) {
            alert("No items selected for deletion.");
            return;
        }

        if (confirm('Are you sure you want to delete this item?')) {
            // Call your API to delete items
            fetch(`/api/${entityName}/${self.selectedItems()}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token if needed
                }
            })
                .then(response => {
                    if (response.ok) {
                        // Remove the item from the observable array
                        // Iterate through the selected items
                        self.selectedItems().forEach(function (selectedId) {
                            // Remove the item from the items array
                            self.items.remove(function (item) {
                                return item.id == selectedId; // Assuming each item has an `id` property
                            });
                        });
                        self.selectedItems.removeAll();

                        console.log('Item deleted successfully', self.selectedItems());
                        self.fetchItems()
                    } else {
                        console.error('Error deleting item:', response.statusText);
                    }
                })
                .catch(error => console.error('Error deleting item:', error));
        }
    };

    // Function to edit selected item
    self.editItem = function () {
        if (self.selectedItems().length !== 1) {
            alert("Please select exactly one item to edit.");
            return;
        }

        var itemId = self.selectedItems()[0]; // Get the ID of the selected item
        // Redirect or open edit modal for the selected item
        window.location.href = '/edit-item/' + itemId; // Change URL accordingly
    };

    // Function to select or deselect all items
    self.selectAll = function () {
        var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        // self.selectedItems.removeAll();
        if (checkboxes.length) {
            checkboxes.forEach(function (checkbox) {
                // checkbox.checked = false; // Toggle checkboxes
                // self.selectedItems.removeAll();

                checkbox.checked = !checkbox.checked; // Toggle checkboxes
                if (checkbox.checked) {
                    if (!self.selectedItems().includes(checkbox.value)) {
                        self.selectedItems.push(checkbox.value); // Add to selected items
                    } else {
                        self.selectedItems.remove(checkbox.value); // Remove from selected items    
                    }
                } else {
                    if (!self.selectedItems().includes(checkbox.value)) {
                        self.selectedItems.push(checkbox.value); // Add to selected items
                    } else {
                        self.selectedItems.remove(checkbox.value); // Remove from selected items    
                    }
                }
            });
        }
    };

    // Function to check if an item is selected
    self.isSelected = function (itemId) {
        return ko.computed(function () {
            return self.selectedItems().includes(String(itemId));
        });
    };

    // Function to update selectedItems based on checkbox change
    self.updateSelectedItems = function (itemId, isChecked) {

        // if (isChecked) {

        // Add the selected item ID to the selectedItems array
        if (!self.selectedItems().includes(String(itemId))) {
            let cek = self.selectedItems();
            debugger
            self.selectedItems.push(String(itemId));
            // }
        } else {
            // Remove the item ID from the selectedItems array
            self.selectedItems.remove(String(itemId));
        }

        console.log(self.selectedItems(), 'SOBBB');

    };

    // Navigate to the next page
    self.nextPage = function () {
        if (self.currentPage() < self.totalPages()) {
            self.currentPage(self.currentPage() + 1);
            self.fetchItems();
        }
    };

    // Navigate to the previous page
    self.previousPage = function () {
        if (self.currentPage() > 1) {
            self.currentPage(self.currentPage() - 1);
            self.fetchItems();
        }
    };

    // Check if there is a next page
    self.hasNextPage = ko.computed(function () {
        console.log('OKK', self.currentPage() < self.totalPages());
        return self.currentPage() < self.totalPages();
    });

    // Check if there is a previous page
    self.hasPreviousPage = ko.computed(function () {
        return self.currentPage() > 1;
    });

    // Search functionality
    self.performSearch = function () {
        self.currentPage(1); // Reset to the first page on search
        self.fetchItems();
    };

    // Sort functionality (assuming you have a sorting mechanism)
    self.sortItems = function (column) {
        if (self.sortingColumn() === column) {
            self.sortingOrder(self.sortingOrder() === 'asc' ? 'desc' : 'asc'); // Toggle sort order
        } else {
            self.sortingColumn(column);
            self.sortingOrder('asc'); // Default to ascending
        }
        self.fetchItems(); // Re-fetch items based on the new sorting
    };

    // Initial fetch on creation
    self.fetchItems();
}
