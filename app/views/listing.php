<?php include('partials/head.php'); ?>

<div class="container py-3">
    <div class="header bg-dark text-white">
        <h2 class="text-center">
            <?= ucwords($post->name) ?>
        </h2>
    </div>
    <div class="product-details">
        <div class="row p-3">
            <div class="col-md-6">
                <img src="<?= $post->image ?>" alt="<?= $post->name ?>" class="img-fluid mb-3 rounded">
            </div>
            <div class="col-md-6 side-info">
                <div class="availability my-2">Available Quantity : <span class="text-success">
                        <?= $post->available_quantity ?>
                        <?= ucfirst($post->unit) ?>
                    </span></div>
                <div class="price my-2"><span class="text-default">
                        <?= '₦ '.$post->price ?> Per
                        <?= ucfirst($post->unit) ?>
                </div>
                <div class="category my-2">
                    <?= $post->category ?>
                </div>
                <div class="time-created my-4">Time Created:
                    <?= formatCustomDate($post->created_at); ?>
                </div>
                <?php if($auth->isAuthenticated()): ?>
                    <?php if($post->user_id == $auth->getUserId()): ?>
                        <div class="btn-group" role="group">
                            <a href="/edit-post?id=<?= $post->id ?>" class="btn btn-sm btn-primary mr-2">Edit</a>
                            <a href="/delete-post?id=<?= $post->id ?>" id="delete-post" class="btn btn-sm btn-danger">Delete</a>
                        </div>
                        <form id="delete-post-form" action="/delete-post" method="POST">
                            <input type="hidden" name="postId" value="<?= $post->id ?>">
                        </form>

                        <p class="text-warning">You Posted this</p>
                    <?php else: ?>
                        <a href="/profile?id=<?= $post->user_id ?>" class="btn btn-warning">Contact Seller</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" class="btn btn-warning">Contact Seller</a>
                <?php endif; ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 content">
                <div class="summernote-content">
                    <?= $post->content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize Summernote
        $('#summernote-content').summernote({
            readOnly: true, // Make it read-only
            height: 350,
            toolbar: false // Hide the toolbar if needed
        });

        $('#delete-post').click(function(e){
            e.preventDefault();
            if(confirm('Are you sure you want to delete this post?')){
                $('#delete-post-form').submit();
            }
        })
    });
</script>
</body>

</html>