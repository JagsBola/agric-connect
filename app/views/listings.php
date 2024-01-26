<?php $additionalStyles = ['css/listings.css']; ?>
<?php include('partials/head.php'); ?>
<!-- Product Categories -->
<div class="container listing-container">
    <div class="header">
        <h3>Categories</h3>
    </div>
    <div class="row">
        <?php foreach(array_slice($post->getCategories(), 0, 5) as $key => $cat): ?>
            <div class="col">
                <a href="category-listings?id=<?= $cat->id; ?>">        
                    <img src="images/categories/<?= $cat->image ?>" alt="<?= $cat->name ?>" class="img-fluid">
                    <div class="text-center pt-3">
                        <?= $cat->name ?>
                    </div>
                </a>
            </div>
            <?php if($key !== 4): ?>
                <div class="px-1"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Latest Listings -->
<div class="container listing-container">
    <div class="header">
        <h3>New Listings</h3>
    </div>
    <div class="row">
        <!-- Product Card Example (Repeat as needed) -->
        <?php foreach($posts as $key => $item): ?>
            <div class="col">
                <a href="listing?id=<?= $item->id; ?>">
                    <img src="<?= $item->image ?>" alt="<?= $item->name ?? 'Test' ?>" class="img-fluid">
                    <div class="pt-3">
                        <div class="text-success bold">
                            <?= ucwords($item->name) ?>
                        </div>
                        <div>
                            <span class="bold">
                                <?= '₦ '.$item->price ?>
                            </span>
                            <span class="small">Per</span>
                            <span>
                                <?= ucfirst($item->unit) ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <?php if(($key + 1) % 5 === 0): ?>
                <div class="px-1"></div>
            </div>
            <div class="row">
            <?php else: ?>
                <div class="px-1"></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-3">
        <div class="col">
            <?php include('partials/pagination.php'); ?>
        </div>
    </div>
</div>


<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>