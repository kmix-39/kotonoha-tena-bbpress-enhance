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

function disableCriteriaElements(elements) {
  elements.forEach(function (element) {
    const selectTagElements = element.getElementsByTagName("select");
    for (var count = 0; count < selectTagElements.length; count++) {
      selectTagElements[count].disabled = true;
    }
    element.style.display = "none";
  });
}

function enableCriteriaElements(elements) {
  elements.forEach(function (element) {
    const selectTagElements = element.getElementsByTagName("select");
    for (var count = 0; count < selectTagElements.length; count++) {
      selectTagElements[count].disabled = false;
    }
    element.style.display = "block";
  });
}

function changeFormCriteria(element) {
  const formElement = element.closest(".bbp-search-form");
  const taxonomiesElements = formElement.querySelectorAll(
    ".ktpp-bbp-criteria-topic"
  );
  disableCriteriaElements(taxonomiesElements);
  const type = element.value;
  if ("" !== type && KTPP_BBPRESS_ENHANCE.topicType === type) {
    enableCriteriaElements(taxonomiesElements);
  }
}

window.addEventListener("load", function () {
  const searchFormElements = document.querySelectorAll(".bbp-search-form");
  if ("undefined" !== typeof searchFormElements) {
    searchFormElements.forEach(function (searchFormElement) {
      const typeElement = searchFormElement.querySelector(
        '[data-name="bbp_type"]'
      );
      if (null !== typeElement) {
        changeFormCriteria(typeElement);
      }
    });
  }
});

document
  .querySelector('[data-name="bbp_type"]')
  .addEventListener("change", function () {
    changeFormCriteria(this);
  });
