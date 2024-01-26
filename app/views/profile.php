<?php $additionalStyles = ['css/profile.css']; ?>
<?php include('partials/head.php'); ?>

<div class="container py-3">
    <!-- <div class="header bg-dark text-white">
        <h3 class="text-center">
            <?= ucwords($post->name) ?>
        </h3>
    </div> -->

    <div class="row d-flex justify-content-center">

        <div class="col-sm-12 col-md-4">

            <div class="card p-3 py-4">

                <div class="text-center">
                    <img src="https://i.imgur.com/bDLhJiP.jpg" width="100" class="rounded-circle">
                </div>

                <div class="text-center mt-3">
                    <span class="bg-secondary p-1 px-4 rounded text-white">
                        <?= ucwords($user->type) ?>
                    </span>
                    <h5 class="mt-2 mb-0">
                        <?= $user->name ?>
                    </h5>

                    <div class="px-4 mt-1">
                        <p class="fonts">
                            <?= $post->about ?? '' ?>
                        </p>

                    </div>

                    <!-- <ul class="social-list">
                        <li><i class="fa fa-facebook"></i></li>
                        <li><i class="fa fa-dribbble"></i></li>
                        <li><i class="fa fa-instagram"></i></li>
                        <li><i class="fa fa-linkedin"></i></li>
                        <li><i class="fa fa-google"></i></li>
                    </ul> -->

                    <div class="buttons">

                        <a href="/chats?id=<?= $user->id ?>" class="btn btn-outline message-button px-4">Message</a>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-12 col-md-8">
            <!-- Latest Listings -->

            <h3 class="text-center">
                <span class="text-">
                    <?= ucwords($user->name)."'s Recent Listings" ?>
                </span>


            </h3>

            <!-- <div class="row"> -->
            <!-- Product Card Example (Repeat as needed) -->
            <?php foreach($posts as $key => $item): ?>

                <li>
                    <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top"
                        href="/listing?id=<?= $item->id ?>">

                        <img src="<?= $item->image ?>" alt="<?= $item->name ?? 'Test' ?>" class="bd-placeholder-img"
                            width="<?= $key < 2 ? '80%' : '70%' ?>" height="<?= $key < 2 ? '96' : '180' ?>">

                        <div class="col-lg-8">

                            <h6 class="<?= $item > 1 ? 'mb-2' : 'mb-0' ?>" style="color: #212529;">
                                <?= $item->name ?>
                            </h6>
                            <span style="color: #212529;">
                                <small class="text-body-secondary ">
                                    <?= date('F j, Y', strtotime($item->created_at)); ?>
                                </small>

                                <small class="float-right mt-1">
                                    <span class="text-success">
                                        <?= '₦ '.$item->price ?>
                                    </span> per <span class="text-warning">
                                        <?= ucfirst($item->unit) ?>
                                    </span>
                                </small>
                            </span>

                        </div>
                    </a>
                </li>

                <?php if($key >= 1): ?>

                </div>

                <div class="col-sm-12 col-md-12">

                <?php endif; ?>

            <?php endforeach; ?>
        </div>

    </div>



    <!-- Pagination -->
    <!-- <div class="row mt-3">
        <div class="col">
            <?php // include('partials/pagination.php'); ?>
        </div>
    </div> -->


</div>


<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>