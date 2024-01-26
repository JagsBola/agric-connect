<?php $additionalStyles = ['css/post.css']; ?>
<?php include('partials/head.php'); ?>

<div class="container">
    <!-- Add Product Form -->
    <div class="container mt-5">
        <h2 class="mb-4 header">Add Listing</h2>
        <form id="addProductForm" method="POST" action="/update-post" enctype="multipart/form-data"
            data-parsley-validate>
            <input type="hidden" value="<?= $post->id ?>" name="postId">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $post->name ?>" placeholder="Enter name" required
                        data-parsley-trigger="change">
                    <div class="parsley-errors-list"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category" required data-parsley-trigger="change">
                        <option value="">Select Category</option>

                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= $cat->id == $post->category_id ? 'selected' : '' ?>>
                                <?= $cat->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="parsley-errors-list"></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="image">Image:</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" <?= $post->image ? '' : 'required' ?>
                            data-parsley-trigger="change">
                        <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                    <div class="parsley-errors-list"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= $post->price ?>" placeholder="Enter price" required
                        data-parsley-trigger="change">
                    <div class="parsley-errors-list"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="unit">Unit:</label>
                    <input type="text" class="form-control" id="unit" name="unit" value="<?= $post->unit ?>" placeholder="Enter unit" required
                        data-parsley-trigger="change">
                    <div class="parsley-errors-list"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= $post->location ?>" placeholder="Enter location"
                        required data-parsley-trigger="change">
                    <div class="parsley-errors-list"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="availableQuantity">Available Quantity:</label>
                    <input type="number" class="form-control" id="availableQuantity" name="available_quantity" value="<?= $post->available_quantity ?>"
                        placeholder="Enter available quantity" required data-parsley-trigger="change">
                    <div class="parsley-errors-list"></div>
                </div>
            </div>

            <label for="description">Description:</label>
            <textarea id="summernote" name="content" data-parsley-trigger="change" placeholder="Description">
                <?= $post->content ?>
            </textarea>
            <div class="parsley-errors-list"></div>

            <div class="form-row my-3">
                <button type="submit" class="btn post-btn float-right">Add listing</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include Parsley JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

<!-- Include Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            placeholder: 'Hello Bootstrap 4',
            tabsize: 2,
            height: 350,
            disableDragAndDrop: true, // Disable drag-and-drop for files
            // callbacks: {
            //     onImageUpload: function (files) {
            //         alert('Image upload is disabled. Please use URLs.');
            //     },
            //     onMediaDelete: function (target) {
            //         alert('Media deletion is disabled.');
            //         return false; // Prevent media deletion
            //     }
            // },
            // buttons: {
            //     file: function () {
            //         alert('File upload is disabled. Please use URLs.');
            //     },
            //     image: function () {
            //         alert('Image upload is disabled. Please use URLs.');
            //     }
            // }

        });
    });

    // Initialize Parsley
    $('#addProductForm').parsley();
</script>

</body>

</html>