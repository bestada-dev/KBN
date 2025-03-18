
<div class="chart-container" style="margin: 24px 0;">
    <div class="chart-container-header">
        <h2>Available Property</h2>
    </div>

    <div class="list-container">
        @foreach ($master_categories as $category)
            <div class="list-item">
                <div class="icon">
                    <img src="{{ Storage::url($category->icon) }}" alt="Icon">
                </div>
                <div class="item-content">
                    <span class="title">{{ $category->name }}</span>
                    <span class="value">{{ $category->properties_count }}</span>
                </div>
            </div>
        @endforeach
    </div>

</div>