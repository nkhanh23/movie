
/**
 * Post new comment
 */
function postComment(event) {
    event.preventDefault();

    const contentInput = document.getElementById('commentContent');
    const content = contentInput.value.trim();

    if (!content) {
        alert("Vui lòng nhập nội dung bình luận!");
        return;
    }

    const btnSubmit = event.target.querySelector('button[type="submit"]');
    const oldText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = 'Đang gửi...';

    const formData = new FormData(document.getElementById('commentForm'));

    fetch('/movie/api/post-comment', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                contentInput.value = '';

                const newCommentHTML = `
    <div class="flex gap-4 group animate-[fade-in_0.5s]" id="comment-${data.data.id}">
        <div class="size-10 rounded-full border border-white/10 shrink-0 overflow-hidden">
             <img src="${data.data.avatar}" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 comment-body">
            <div class="flex items-center justify-between mb-1">
                <h4 class="text-white font-bold text-sm">${data.data.fullname}</h4> 
                
                <div class="flex items-center gap-3">
                    <span class="text-white/40 text-xs">Vừa xong</span>
                </div>
            </div>
            
            <div class="flex text-yellow-500 text-[14px] mb-2">
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
                <span class="material-symbols-outlined text-[16px]">star</span>
            </div>

            <p class="text-white/80 text-sm leading-relaxed">${data.data.content}</p>
            
            <div class="flex gap-4 mt-3">
                <button onclick="toggleLike(${data.data.id}, this)" class="text-white/40 hover:text-primary text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                    <span class="like-count font-bold hidden">0</span>
                </button>
                <button class="btn-reply text-white/40 hover:text-white text-xs flex items-center gap-1"
                        data-id="${data.data.id}"
                        data-name="${data.data.fullname}"
                        data-level="0">
                    Reply
                </button>
                <button onclick="deleteComment(${data.data.id})" class="text-white/20 hover:text-red-500 text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">delete</span> Xóa
                </button>
            </div>
        </div>
    </div>
`;
                document.getElementById('comment-list').insertAdjacentHTML('afterbegin', newCommentHTML);

            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
        })
        .finally(() => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = oldText;
        });
}

/**
 * Delete comment
 */
function deleteComment(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa bình luận này không?')) return;

    const formData = new FormData();
    formData.append('comment_id', id);

    fetch('/movie/api/delete-comment', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const commentItem = document.getElementById('comment-' + id);

                if (data.action === 'hide' && commentItem) {
                    const contentDiv = commentItem.querySelector('.text-white\\/80');
                    if (contentDiv) {
                        contentDiv.innerText = data.new_content;
                        contentDiv.classList.remove('text-white/80');
                        contentDiv.classList.add('text-red-500', 'italic');
                    }
                    return;
                }

                if (commentItem) {
                    const level = parseInt(commentItem.getAttribute('data-level')) || 0;

                    if (level === 0) {
                        const wrapper = commentItem.closest('.comment-thread-wrapper');
                        if (wrapper) {
                            wrapper.remove();
                        } else {
                            commentItem.remove();
                        }
                    } else {
                        let nextSibling = commentItem.nextElementSibling;
                        while (nextSibling) {
                            if (nextSibling.classList.contains('comment-item')) {
                                const nextLevel = parseInt(nextSibling.getAttribute('data-level')) || 0;

                                if (nextLevel > level) {
                                    const nodeToRemove = nextSibling;
                                    nextSibling = nextSibling.nextElementSibling;
                                    nodeToRemove.remove();
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }
                        }

                        commentItem.remove();
                    }
                }

            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi kết nối server.');
        });
}

/**
 * Toggle like/unlike comment
 */
function toggleLike(commentId, btnElement) {
    if (btnElement.classList.contains('processing')) return;
    btnElement.classList.add('processing');

    const formData = new FormData();
    formData.append('comment_id', commentId);

    fetch('/movie/api/like-comment', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const countSpan = btnElement.querySelector('.like-count');

                if (data.action === 'liked') {
                    btnElement.classList.remove('text-white/40');
                    btnElement.classList.add('text-primary');
                } else {
                    btnElement.classList.add('text-white/40');
                    btnElement.classList.remove('text-primary');
                }

                if (data.likes > 0) {
                    countSpan.innerText = data.likes;
                    countSpan.classList.remove('hidden');
                } else {
                    countSpan.classList.add('hidden');
                }
            } else {
                alert(data.message);
                if (data.message.includes('đăng nhập')) {
                    window.location.href = '/login';
                }
            }
        })
        .catch(err => console.error(err))
        .finally(() => btnElement.classList.remove('processing'));
}

/**
 * Load more comments
 */
function loadMoreComments() {
    const hiddenThreads = document.querySelectorAll('.comment-hidden-thread');

    let count = 0;
    hiddenThreads.forEach(thread => {
        if (count < 10) {
            thread.classList.remove('hidden', 'comment-hidden-thread');
            thread.classList.add('animate-fade-in-up');
            count++;
        }
    });

    const remaining = document.querySelectorAll('.comment-hidden-thread').length;
    const countSpan = document.getElementById('remainingCount');

    if (remaining > 0) {
        if (countSpan) countSpan.innerText = remaining;
    } else {
        const btnContainer = document.getElementById('loadMoreContainer');
        if (btnContainer) btnContainer.remove();
    }
}

/**
 * Handle reply form submit
 */
function handleReplySubmit(e, form) {
    e.preventDefault();

    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');
    const parentId = formData.get('parent_id');
    const replyToName = formData.get('reply_to_name');

    const content = formData.get('content').trim();
    if (!content) {
        alert('Vui lòng nhập nội dung bình luận!');
        return;
    }

    btnSubmit.disabled = true;
    btnSubmit.innerText = 'Đang gửi...';

    fetch('/movie/api/reply-comment', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                form.closest('.reply-form-wrapper').remove();
                renderReplyItem(res.data, parentId, replyToName);
            } else {
                alert(res.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi kết nối server: ' + err.message);
        })
        .finally(() => {
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Gửi trả lời';
            }
        });
}

/**
 * Render reply item
 */
function renderReplyItem(data, parentId, parentName = null) {
    const parentComment = document.getElementById('comment-' + parentId);

    let parentLevel = 0;
    if (parentComment.hasAttribute('data-level')) {
        parentLevel = parseInt(parentComment.getAttribute('data-level'));
    }
    const newLevel = parentLevel + 1;

    const marginLeftPx = newLevel * 48;
    const styleIndent = `margin-left: ${marginLeftPx}px`;

    const nameTag = parentName ?
        `<span class="inline-flex items-center bg-white/10 hover:bg-white/10 backdrop-blur-md border border-white/10 px-2 py-0.5 rounded text-white font-bold text-xs mr-1 transition-colors cursor-pointer">@${parentName}</span>` :
        '';

    const html = `
    <div id="comment-${data.id}" 
         class="flex gap-4 group comment-item animate-fade-in-down border-l-2 border-white/10 pl-4 mt-4" 
         style="${styleIndent}"
         data-level="${newLevel}">
        
        <img src="${data.avatar}" 
             class="size-8 rounded-full border border-white/10 shrink-0 object-cover"
             onerror="this.src='https://i.pravatar.cc/150?u=default'">

        <div class="flex-1 comment-body">
            <div class="flex items-center gap-2 mb-1">
                <h4 class="text-white font-bold text-xs">${data.fullname}</h4>
                <span class="text-white/40 text-[10px]">Vừa xong</span>
            </div>

            <div class="text-white/80 text-sm leading-relaxed">
                ${nameTag}
                ${data.content}
            </div>

            <div class="flex gap-4 mt-2">
                <button onclick="toggleLike(${data.id}, this)" 
                    class="btn-like text-white/40 hover:text-primary text-xs flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                    <span class="like-count font-bold hidden">0</span>
                </button>

                <button class="btn-reply text-white/40 hover:text-white text-xs flex items-center gap-1"
                        data-id="${data.id}"
                        data-name="${data.fullname}"
                        data-level="${newLevel}">
                    Reply
                </button>

                <button onclick="deleteComment(${data.id})" 
                    class="text-white/20 hover:text-red-500 text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">delete</span> Xóa
                </button>
            </div>
        </div>
    </div>
`;

    parentComment.insertAdjacentHTML('afterend', html);
}

/**
 * Initialize comment events (reply, cancel)
 */
function initCommentEvents() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentMovieId = urlParams.get('id');

    document.body.addEventListener('click', function (e) {
        // Reply button
        const btn = e.target.closest('.btn-reply');
        if (btn) {
            e.preventDefault();
            const commentId = btn.getAttribute('data-id');
            const parentName = btn.getAttribute('data-name');
            const parentLevel = parseInt(btn.getAttribute('data-level')) || 0;

            const commentItem = document.getElementById('comment-' + commentId);
            const contentBody = commentItem.querySelector('.comment-body');
            const existingForm = contentBody.querySelector('.reply-form-wrapper');

            if (existingForm) {
                existingForm.remove();
            } else {
                const replyFormHtml = `
                <div class="reply-form-wrapper mt-4 animate-fade-in-down ml-2">
                    <form onsubmit="handleReplySubmit(event, this)" class="flex flex-col gap-2">
                        <input type="hidden" name="parent_id" value="${commentId}">
                        <input type="hidden" name="movie_id" value="${currentMovieId}">
                        <input type="hidden" name="reply_to_name" value="${parentName}">
                        <textarea name="content" rows="2" 
                            class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-sm text-white focus:outline-none focus:border-primary placeholder-white/20 transition-all"
                            placeholder="Trả lời ${parentName}..."></textarea>
                        
                        <div class="flex justify-end gap-2">
                            <button type="button" class="btn-cancel-reply text-xs text-white/40 hover:text-white px-3 py-1">Hủy</button>
                            <button type="submit" class="bg-primary hover:bg-primary/80 text-white text-xs font-bold px-4 py-2 rounded transition-colors">
                                Gửi trả lời
                            </button>
                        </div>
                    </form>
                </div>
            `;

                contentBody.insertAdjacentHTML('beforeend', replyFormHtml);

                const textarea = contentBody.querySelector('textarea');
                if (textarea) textarea.focus();
            }
        }

        // Cancel reply button
        if (e.target && e.target.classList.contains('btn-cancel-reply')) {
            e.preventDefault();
            const formWrapper = e.target.closest('.reply-form-wrapper');
            if (formWrapper) {
                formWrapper.remove();
            }
        }
    });
}

// Auto-init when DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCommentEvents);
} else {
    initCommentEvents();
}
