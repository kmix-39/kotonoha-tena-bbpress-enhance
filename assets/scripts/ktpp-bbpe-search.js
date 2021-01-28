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
