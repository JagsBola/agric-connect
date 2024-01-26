<?php $additionalStyles = ['css/chats.css']; ?>
<?php include('partials/head.php'); ?>
<div class="container py-3">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app">
                <div id="plist" class="people-list">
                    <!-- <h2 class="text-center text-primary">Users </h2> -->
                    <!-- <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div> -->
                    <ul class="list-unstyled chat-list mt-2 mb-0">
                        <?php foreach($chats as $message): ?>
                            <a href="/chats?id=<?= $message['party_id'] ?>" style="color:unset;">
                                <li class="clearfix">

                                    <img src="<?= $message['party_image'] ?? 'https://via.placeholder.com/150' ?>" alt="avatar">
                                    <div class="about" style="overflow: hidden; text-overflow: ellipsis;">
                                        <div class="name">
                                            <?= $message['name'] ?>
                                        </div>
                                        <div class="status"> <?= $message['user_type'] ?> </div>
                                    </div>

                                </li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="chat">
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="<?= $party->image ?? 'https://via.placeholder.com/150' ?>" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">
                                        <?= $party->name ?>
                                    </h6>
                                    <small>
                                        <?= $party->type ?>
                                    </small>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6 hidden-sm text-right">
                                <a href="javascript:void(0);" class="btn btn-outline-secondary"><i
                                        class="fa fa-camera"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-primary"><i
                                        class="fa fa-image"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-info"><i
                                        class="fa fa-cogs"></i></a>
                                <a href="javascript:void(0);" class="btn btn-outline-warning"><i
                                        class="fa fa-question"></i></a>
                            </div> -->
                        </div>
                    </div>
                    <div id="chat-history" class="chat-history">
                        <ul class="m-b-0">

                            <?php foreach($chat as $message): ?>
                                <li class="clearfix">
                                    <?php if($message['sender_id'] == $party->id): ?>
                                        <div class="message-data text-right">
                                            <span class="message-data-time">
                                                <?= formatCustomDate($message['created_at']) ?>
                                            </span>
                                            <img src="<?= $party->image ?? 'https://via.placeholder.com/150' ?>" alt="avatar">
                                        </div>

                                        <div class="message other-message float-right">
                                            <?= $message['decrypted_message'] ?>
                                        </div>
                                    <?php else: ?>

                                        <div class="message-data">
                                            <span class="message-data-time">
                                                <?= formatCustomDate($message['created_at']) ?>
                                            </span>
                                        </div>
                                        <div class="message my-message">
                                            <?= $message['decrypted_message'] ?>
                                        </div>
                                    <?php endif; ?>
                                </li>

                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="chat-message clearfix" style="position: relative;">
                        <div class="row">

                            <div class="col-10">
                                <form id="send-message-form" action="/send-message" method="POST">
                                    <input type="hidden" name="partyId" value="<?= $party->id ?? '' ?>">
                                    <textarea id="messageTextarea" name="message" class="form-control"
                                        placeholder="Enter text here..." oninput="resizeTextarea(this)"></textarea>
                                </form>
                            </div>

                            <div class="col-2">
                                <button id="send-message" type="submit" class="btn btn-warning float-right"
                                    <?= $party->id ? '' : 'disabled' ?>>
                                    <i class="fa fa-send"></i></span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap and Font Awesome JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>

    // get the chat history element
    const chatHistory = document.querySelector('#chat-history');
    // Smooth scroll down to the bottom
    chatHistory.scrollTop = chatHistory.scrollHeight;


    const initialHeight = document.querySelector('#messageTextarea').clientHeight;

    function resizeTextarea(textarea) {
        textarea.style.height = 'auto';

        // Limit the height to a maximum of three times the initial height
        const maxHeight = 2 * initialHeight;
        const newHeight = Math.min(textarea.scrollHeight, maxHeight);

        textarea.style.height = newHeight + 'px';
    }


    $('#send-message').click(function () {
        $('#send-message-form').submit();
    });
</script>
</body>

</html>