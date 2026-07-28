/**
 * SherBlock Admin JavaScript
 *
 * Handles AJAX interactions, copy-to-clipboard, re-index progress,
 * and dismissible admin notices.
 *
 * @package SherBlock
 */

/* global jQuery, sherblockAdmin */
(function ($) {
	'use strict';

	var SherblockAdmin = {

		/**
		 * Initialize all admin behaviors.
		 */
		init: function () {
			this.initCopyButtons();
			this.initReindex();
			this.initDismissibleNotices();
		},

		/**
		 * Copy-to-clipboard buttons for block names.
		 */
		initCopyButtons: function () {
			$(document).on('click', '.sherblock-copy-btn', function (e) {
				e.preventDefault();

				var $btn = $(this);
				var text = $btn.data('copy');

				if (!text) {
					return;
				}

				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(text).then(function () {
						SherblockAdmin.showCopied($btn);
					});
				} else {
					// Fallback for older browsers.
					var $temp = $('<textarea>');
					$('body').append($temp);
					$temp.val(text).trigger('select');
					document.execCommand('copy');
					$temp.remove();
					SherblockAdmin.showCopied($btn);
				}
			});
		},

		/**
		 * Show copied feedback on button.
		 */
		showCopied: function ($btn) {
			var originalHtml = $btn.html();
			$btn.addClass('copied')
				.html('<span class="dashicons dashicons-yes"></span>');

			setTimeout(function () {
				$btn.removeClass('copied').html(originalHtml);
			}, 1500);
		},

		/**
		 * Re-index with batched AJAX calls and progress bar.
		 */
		initReindex: function () {
			$(document).on('click', '.sherblock-reindex-btn', function (e) {
				e.preventDefault();

				if (!sherblockAdmin || !sherblockAdmin.ajaxUrl) {
					return;
				}

				var $btn = $(this);
				var $wrap = $('.sherblock-reindex-wrap');
				var $progressFill = $wrap.find('.progress-fill');
				var $progressText = $wrap.find('.progress-text');
				var $status = $wrap.find('.sherblock-reindex-status');

				$btn.prop('disabled', true).text(sherblockAdmin.i18n.indexing || 'Indexing...');
				$wrap.show();
				$progressFill.css('width', '0%');
				$progressText.text('0%');
				$status.text(sherblockAdmin.i18n.preparing || 'Preparing...');

				SherblockAdmin.runReindexBatch(0, $btn, $progressFill, $progressText, $status);
			});
		},

		/**
		 * Run a single re-index batch via AJAX.
		 */
		runReindexBatch: function (offset, $btn, $progressFill, $progressText, $status) {
			$.ajax({
				url: sherblockAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'sherblock_reindex',
					nonce: sherblockAdmin.nonce,
					offset: offset
				},
				success: function (response) {
					if (!response.success) {
						$status.text(response.data && response.data.message ? response.data.message : 'Error occurred.');
						$btn.prop('disabled', false).text(sherblockAdmin.i18n.reindex || 'Re-index All Content');
						return;
					}

					var data = response.data;
					var percent = data.total > 0 ? Math.round((data.processed / data.total) * 100) : 0;

					$progressFill.css('width', percent + '%');
					$progressText.text(percent + '%');
					$status.text(
						(sherblockAdmin.i18n.processed || 'Processed') + ' ' +
						data.processed + ' / ' + data.total + ' ' +
						(sherblockAdmin.i18n.posts || 'posts')
					);

					if (data.done) {
						$progressFill.css('width', '100%');
						$progressText.text('100%');
						$status.text(sherblockAdmin.i18n.complete || 'Indexing complete!');
						$btn.prop('disabled', false).text(sherblockAdmin.i18n.reindex || 'Re-index All Content');

						// Reload page after a short delay to update stats.
						setTimeout(function () {
							window.location.reload();
						}, 1500);
					} else {
						SherblockAdmin.runReindexBatch(data.next_offset, $btn, $progressFill, $progressText, $status);
					}
				},
				error: function () {
					$status.text(sherblockAdmin.i18n.error || 'An error occurred. Please try again.');
					$btn.prop('disabled', false).text(sherblockAdmin.i18n.reindex || 'Re-index All Content');
				}
			});
		},

		/**
		 * Dismissible admin notices with localStorage persistence.
		 */
		initDismissibleNotices: function () {
			$(document).on('click', '.sherblock-notice.is-dismissible .notice-dismiss', function () {
				var $notice = $(this).closest('.sherblock-notice');
				var noticeId = $notice.data('notice-id');

				if (noticeId && window.localStorage) {
					var dismissed = JSON.parse(localStorage.getItem('sherblock_dismissed_notices') || '[]');
					if (dismissed.indexOf(noticeId) === -1) {
						dismissed.push(noticeId);
						localStorage.setItem('sherblock_dismissed_notices', JSON.stringify(dismissed));
					}
				}
			});

			// Hide already-dismissed notices on page load.
			if (window.localStorage) {
				var dismissed = JSON.parse(localStorage.getItem('sherblock_dismissed_notices') || '[]');
				$('.sherblock-notice.is-dismissible').each(function () {
					var noticeId = $(this).data('notice-id');
					if (noticeId && dismissed.indexOf(noticeId) !== -1) {
						$(this).hide();
					}
				});
			}
		}
	};

	$(document).ready(function () {
		SherblockAdmin.init();
	});

})(jQuery);
