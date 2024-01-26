<?php $additionalStyles = ['css/profile.css']; ?>
<?php include('partials/head.php'); ?>

<div class="container py-3">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-12 col-md-8">
            <h3 class="text-center">
                Edit Profile
            </h3>

            <br>
            <?php include('partials/notification.php'); ?>
            <!-- Profile Image -->
            <div class="text-center">
                <img id="image-preview" src="<?= $auth->image ?? 'https://via.placeholder.com/150' ?>" width="180" height="200" class="rounded-circle">
            </div>

            <!-- Profile Edit Form -->
            <form action="/update-profile" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $auth->name ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea class="form-control" id="address" name="address" required><?= $auth->address ?></textarea>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Profile Image:</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            <small class="form-text text-muted">Upload a new profile image.</small>
                        </div>
                    </div>


                    <div class="col-6">

                        <div class="buttons my-4">
                            <button type="submit" class="btn btn-outline message-button px-4 float-right">Save
                                Changes</button>
                            <!-- <a href="/chats?id=<?= $user->id ?>" class="btn btn-outline message-button px-4">Message</a> -->

                        </div>

                    </div>
                </div>
                <!-- <button type="submit" class="btn btn-primary">Save Changes</button> -->
            </form>
        </div>
    </div>
</div>


<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>