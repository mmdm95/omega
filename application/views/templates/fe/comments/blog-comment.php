<?php foreach ($comments as $comment): ?>
    <li class="depth-1">
        <div class="media">
            <div>
                <a href="javascript:void(0);" class="cmnt_avatar">
                    <img src="<?= base_url(PROFILE_DEFAULT_IMAGE); ?>" alt=""
                         class="media-object rounded-circle img-xlg-height img-thumbnail">
                </a>
            </div>
            <div class="media-body">
                <div class="media_top">
                    <div class="heading_left">
                        <a href="javascript:void(0);">
                            <h6 class="media-heading iranyekan-regular"><?= $comment['name']; ?></h6>
                        </a>
                        <span>
                            <?= jDateTime::date('j F Y', $comment['created_on']); ?>
                        </span>
                    </div>
                </div>
                <p>
                    <?= $comment['body']; ?>
                </p>
            </div>
        </div><!-- ends: .media -->
        <?php if (!empty($comment['respond']) && !empty($comment['respond_on'])): ?>
            <ul class="children list-unstyled">
                <!-- Nested media object -->
                <li class="depth-2">
                    <div class="media">
                        <div>
                            <a href="#" class="cmnt_avatar">
                                <img src="<?= asset_url('fe/img/omega-comment.png'); ?>"
                                     class="media-object rounded-circle img-xlg-height img-thumbnail"
                                     alt="omega">
                            </a>
                        </div>
                        <div class="media-body">
                            <div class="media_top">
                                <div class="heading_left">
                                    <a href="javascript:void(0);">
                                        <h6 class="media-heading iranyekan-regular">
                                            <?= $comment['full_name'] ?? 'اُمگا'; ?>
                                        </h6>
                                    </a>
                                    <span>
                                        <?= jDateTime::date('j F Y', $comment['respond_on']); ?>
                                    </span>
                                </div>
                            </div>
                            <p>
                                <?= $comment['respond']; ?>
                            </p>
                        </div>
                    </div>
                </li>
            </ul><!-- ends: .children -->
        <?php endif; ?>
    </li><!-- ends: .depth-1 -->
<?php endforeach; ?>