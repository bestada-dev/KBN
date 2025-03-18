<style>
    .file-upload-container {
        display: flex;
        align-items: center;
    }

    .file-upload-box {
        border: 2px dashed #ccc;
        padding: 10px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        background-color: #f8f9fa;
        cursor: pointer;
        height: 40px;
    }

    .file-details {
        display: flex;
        align-items: center;
    }

    .file-details i {
        margin-right: 10px;
    }

    .file-name-size {
        font-weight: 500;
    }

    .file-size {
        color: gray;
    }

    .file-icon {
        font-size: 1.2rem;
    }

    .upload-info {
        font-size: 0.875rem;
        color: gray;
        margin-top: 5px;
    }

    .btn-icon {
        background-color: #38a9ff;
        border: none;
        color: white;
        padding: 10px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        height: 42px;
    }

    .btn-icon i {
        font-size: 1.5rem;
    }

    .btn-icon:hover {
        background-color: #2b8ad1;
    }

    .file-view-icon {
        cursor: pointer;
    }

    /* Hide the actual file input */
    .file-input {
        display: none;
    }

    /* Image preview styling */
    .image-preview {
        max-width: 100%;
        margin-top: 10px;
        display: none;
    }

</style>
