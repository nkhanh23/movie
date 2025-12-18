                <div class="lg:col-span-8 flex flex-col gap-6 order-1 lg:order-2">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-white text-xl font-bold">Reviews & Comments</h3>
                        <span class="text-white/60 text-sm">14 comments</span>
                    </div>

                    <div class="glass-panel p-6 rounded-xl">

                        <?php if (!empty($_SESSION['auth'])): ?>

                            <div class="flex gap-4 mb-8">
                                <div class="size-10 rounded-full bg-gradient-to-br from-primary to-purple-600 flex items-center justify-center text-white font-bold text-sm shrink-0 overflow-hidden">
                                    <img src="<?php echo $_SESSION['auth']['avatar']; ?>"
                                        onerror="this.src='https://i.pravatar.cc/150?u=default'"
                                        class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1">
                                    <form id="commentForm" onsubmit="postComment(event)">
                                        <input type="hidden" name="movie_id" value="<?php echo $idMovie; ?>">
                                        <input type="hidden" name="episode_id" value="<?php echo $idEpisode; ?>">

                                        <textarea name="content" id="commentContent"
                                            class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white placeholder-white/40 focus:ring-1 focus:ring-primary focus:border-primary text-sm transition-colors resize-none"
                                            rows="3"
                                            placeholder="Viết cảm nghĩ của bạn về phim..."></textarea>

                                        <div class="flex justify-between items-center mt-3">
                                            <div class="flex gap-1 opacity-50 cursor-not-allowed" title="Tính năng đang phát triển">
                                                <button type="button" class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                                <button type="button" class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                                <button type="button" class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                                <button type="button" class="text-yellow-500 material-symbols-outlined text-[20px]">star</button>
                                                <button type="button" class="text-white/20 material-symbols-outlined text-[20px]">star</button>
                                            </div>

                                            <button type="submit"
                                                class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-lg text-sm font-bold transition-transform active:scale-95 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px]">send</span> Gửi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <?php else: ?>

                            <div class="mb-8 p-6 bg-white/5 border border-dashed border-white/20 rounded-xl text-center">
                                <p class="text-white/60 text-sm md:text-base">
                                    Vui lòng
                                    <a href="<?php echo _HOST_URL; ?>/login" class="text-primary font-bold hover:underline hover:text-primary/80 transition-colors">
                                        đăng nhập
                                    </a>
                                    để tham gia bình luận.
                                </p>
                            </div>

                        <?php endif; ?>
                        <hr class="border-white/10 mb-8">
                        <!-- Danh sách bình luận -->
                        <div id="comment-list" class="flex flex-col mt-8 space-y-4">
                            <?php
                            // 1. Định nghĩa hàm đệ quy hiển thị
                            if (!function_exists('render_comment_recursive')) {
                                function render_comment_recursive($comments, $level = 0, $parentName = null)
                                {
                                    foreach ($comments as $key => $item) {
                                        // Xử lý ẩn/hiện cho Load More (Chỉ áp dụng cho cấp cha - Level 0)
                                        $wrapperAttr = '';
                                        if ($level == 0) {
                                            $hiddenClass = ($key >= 10) ? 'hidden comment-hidden-thread' : '';
                                            echo '<div class="comment-thread-wrapper ' . $hiddenClass . '">';
                                        }

                                        // Style thụt đầu dòng (Dùng Margin inline để tính toán chính xác theo cấp)
                                        $marginLeftPx = $level * 48; // 48px ~ 3rem (ml-12)
                                        $marginStyle = "margin-left: {$marginLeftPx}px";

                                        $borderClass = ($level > 0) ? 'border-l-2 border-white/10 pl-4' : '';
                                        $marginTop = ($level > 0) ? 'mt-4' : 'mt-6';
                                        $avatarSize = ($level == 0) ? 'size-10' : 'size-8';

                                        // Check quyền
                                        $currentUserId = $_SESSION['auth']['id'] ?? 0;
                                        $currentGroupId = $_SESSION['auth']['group_id'] ?? 0;
                                        $isOwnerOrAdmin = ($currentGroupId == 2 || $currentUserId == $item['user_id']);
                            ?>

                                        <div id="comment-<?php echo $item['id']; ?>"
                                            class="flex gap-4 group comment-item animate-fade-in-down <?php echo $borderClass . ' ' . $marginTop; ?>"
                                            style="<?php echo $marginStyle; ?>"
                                            data-level="<?php echo $level; ?>">

                                            <img src="<?php echo $item['avartar']; ?>"
                                                class="<?php echo $avatarSize; ?> rounded-full border border-white/10 shrink-0 object-cover"
                                                onerror="this.src='https://i.pravatar.cc/150?u=default'">

                                            <div class="flex-1 comment-body">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h4 class="text-white font-bold text-<?php echo $level == 0 ? 'sm' : 'xs'; ?>"><?php echo htmlspecialchars($item['fullname']); ?></h4>
                                                    <span class="text-white/40 text-[10px]"><?php echo $item['created_at']; ?></span>
                                                </div>

                                                <div class="<?php echo ($item['content'] === 'Bình luận này đã bị Admin xóa do vi phạm quy tắc cộng đồng.') ? 'text-red-500 italic' : 'text-white/80'; ?> text-sm leading-relaxed">
                                                    <?php if ($level > 0 && $parentName): ?>
                                                        <span class="inline-flex items-center bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-white font-bold text-xs mr-1 transition-colors cursor-pointer">
                                                            @<?php echo htmlspecialchars($parentName); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                                                </div>
                                                <?php
                                                $isLiked = !empty($item['is_liked']) && $item['is_liked'] > 0;
                                                $likeBtnClass = $isLiked ? 'text-primary' : 'text-white/40 hover:text-primary';
                                                $likeIconType = $isLiked ? 'thumb_up_filled' : 'thumb_up';
                                                ?>
                                                <div class="flex gap-4 mt-2">
                                                    <button onclick="toggleLike(<?php echo $item['id']; ?>, this)"
                                                        class="btn-like <?php echo $likeBtnClass; ?> text-xs flex items-center gap-1 transition-colors"
                                                        data-id="<?php echo $item['id']; ?>">
                                                        <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                                                        <span class="like-count font-bold <?php echo ($item['like_count'] > 0) ? '' : 'hidden'; ?>">
                                                            <?php echo $item['like_count']; ?>
                                                        </span>
                                                    </button>
                                                    <button class="btn-reply text-white/40 hover:text-white text-xs flex items-center gap-1"
                                                        data-id="<?php echo $item['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($item['fullname']); ?>"
                                                        data-level="<?php echo $level; ?>">
                                                        Reply
                                                    </button>

                                                    <?php if ($isOwnerOrAdmin): ?>
                                                        <button onclick="deleteComment(<?php echo $item['id']; ?>)" class="text-white/20 hover:text-red-500 text-xs flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-[14px]">delete</span> Xóa
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                            <?php
                                        // ĐỆ QUY: Tìm con của comment này
                                        if (!empty($item['replies'])) {
                                            render_comment_recursive($item['replies'], $level + 1, $item['fullname']);
                                        }

                                        // Đóng thẻ wrapper (Chỉ Level 0)
                                        if ($level == 0) {
                                            echo '<hr class="border-white/5 my-6">';
                                            echo '</div>'; // Đóng div.comment-thread-wrapper
                                        }
                                    }
                                }
                            }

                            // 2. Gọi hàm hiển thị
                            if (!empty($listComments)) {
                                render_comment_recursive($listComments);
                            } else {
                                echo '<p class="text-white/40 text-center text-sm py-4">Chưa có bình luận nào. Hãy là người đầu tiên!</p>';
                            }
                            ?>
                        </div>

                        <?php if (isset($totalComments) && $totalComments > 10): ?>
                            <div class="flex justify-center mt-8" id="loadMoreContainer">
                                <button onclick="loadMoreComments()"
                                    class="text-white/60 hover:text-white text-sm font-medium px-4 py-2 bg-white/5 rounded-lg hover:bg-white/10 transition-colors flex items-center gap-2">
                                    Load more comments
                                    <span id="remainingCount" class="bg-white/10 px-2 py-0.5 rounded text-xs">
                                        <?php echo $totalComments - 10; ?>
                                    </span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>