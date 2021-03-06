/**
 * Created by 路佳 on 14-6-17.
 */
$('.style-list')
  .on('click', 'label', function (event) {
    if (event.target.tagName.toLowerCase() === 'i') {
      return;
    }
    if ($(this).hasClass('disabled')) {
      event.preventDefault();
    }
    var className = /top/.test(this.className) ? 'top' : 'pants';
    $(this).addClass('active')
      .siblings('.' + className).removeClass('active');
  })
  .on('click', '.preview-button', function (event) {
    $('#popup')
      .modal('show')
      .find('.modal-body').html($(event.currentTarget).siblings('img').clone());
    event.stopPropagation();
    event.preventDefault();
  })
  .on('click', '.btn-info', function () {
    var target = $(this);
    target.closest('.row').find('.btn.active').removeClass('active');
    target.addClass('active');
  })
  .on('submit', function (event) {
    var cloth = [];
    $('.tab-pane.active input:checked').each(function () {
      cloth.push(this.value);
    })
    showFlash(cloth.join(','))

    event.preventDefault();
    return false;
  });

function showFlash(data) {
  var flashvars = {
      cloth: data
    }
    , params = {
      menu: "false",
      scale: "noScale",
      allowFullscreen: "true",
      allowScriptAccess: "always",
      bgcolor: "010101",
      wmode: "direct" // can cause issues with FP settings & webcam
    }
    , attributes = {
      id:"DIY"
    };
  $('#clothes-options').hide();
  $('.diy-container').removeClass('hide');
  swfobject.embedSWF(
    "/wp-content/themes/xline/swf/DIY.swf",
    "diy-flash", "100%", "100%", "11.0.0",
    "../swf/expressInstall.swf",
    flashvars, params, attributes);
}

// 回到选择款式的画面
// 由flash调用
function backToForm() {
  setTimeout(function () {
    if (confirm('重新选择款式之后，您需要从头进行设计，确定么？')) {
      $('#DIY').remove();
      $('.diy-container')
        .addClass('hide')
        .html('<div id="diy-flash"></div>');
      $('.style-list').show();
    }
  }, 10);

}
// 显示登录窗体
// 由flash调用
function showLoginModal() {
  $('#login-modal').modal('show')
    .find('.alert')
    .addClass('alert-warning')
    .text('请先登录');
}

function gotoCart() {
  var form = $('<form action="/cart" class="hide" method="post"></form>');
  form.appendTo('body');
  form.submit();
}