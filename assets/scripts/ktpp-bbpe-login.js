(window.bbpajax = {}),
  (BBPAjax_extparam = function () {
    const a = window.bbpajax.params,
      e = window.bbpajax.selector,
      t = document.querySelectorAll(e);
    for (let e = 0; e < t.length; e++)
      "checkbox" == t[e].type
        ? (a[t[e].name] = 0 != t[e].checked ? 1 : 0)
        : (a[t[e].name] = t[e].value);
    return a;
  }),
  (BBPAjax_submit = function (a) {
    jQuery
      .ajax({
        type: "POST",
        url: KTPP_BBPRESS_ENHANCE.endpoint,
        dataType: "json",
        data: a,
      })
      .then(function (a, e) {
        let t = window.bbpajax.success;
        a.data.result !== t.code && (t = window.bbpajax.error),
          null != t.msg && jQuery(t.msg).html(a.data.info),
          "redirect" == t.action && (window.location.href = a.data.info);
      });
  });

WPCustomLogin = function (nonce, is_widget, redirect_to, lostpass) {
  return (
    (window.bbpajax.params = {
      action: "custom_login",
      _ajax_nonce: nonce,
      redirect_to: redirect_to,
      lostpass: lostpass,
    }),
    (window.bbpajax.selector = is_widget
      ? ".bbp_widget_login > form.bbp-login-form input"
      : ":not(.bbp_widget_login) > form.bbp-login-form input"),
    (window.bbpajax.success = {
      code: "login_redirect",
      msg: null,
      action: "redirect",
    }),
    (window.bbpajax.error = {
      code: "e_login",
      msg: ".custom-login-info",
      action: null,
    }),
    BBPAjax_submit(BBPAjax_extparam()),
    !1
  );
};