const nameFieldSelector = 'input[name="nom"], input[name="prenom"]';
const ageFieldSelector = 'input[name="age"]';
const phoneFieldSelector = 'input[name="tel"]';

const allowedNameCharacters = /[\p{L}\s'-]/u;
const invalidNameCharacters = /[^\p{L}\s'-]/gu;
const invalidAgeCharacters = /\D/g;
const invalidPhoneCharacters = /[^0-9 +().-]/g;

const cleanNameValue = (value) => value.replace(invalidNameCharacters, "");
const cleanAgeValue = (value) => value.replace(invalidAgeCharacters, "").slice(0, 3);
const cleanPhoneValue = (value) => {
  const cleanedValue = value.replace(invalidPhoneCharacters, "");
  const withoutExtraPlus = cleanedValue.replace(/\+/g, "");

  return cleanedValue.startsWith("+") ? `+${withoutExtraPlus}` : withoutExtraPlus;
};

const insertCleanedText = (field, text) => {
  if (typeof field.setRangeText === "function") {
    const start = field.selectionStart ?? field.value.length;
    const end = field.selectionEnd ?? field.value.length;

    field.setRangeText(text, start, end, "end");
    field.dispatchEvent(new Event("input", { bubbles: true }));
    return;
  }

  document.execCommand("insertText", false, text);
};

const attachFilteredField = (field, isAllowedCharacter, cleanValue) => {
  field.addEventListener("beforeinput", (event) => {
    if (!event.data) return;

    if (![...event.data].every(isAllowedCharacter)) {
      event.preventDefault();
    }
  });

  field.addEventListener("input", () => {
    const cleanedValue = cleanValue(field.value);

    if (field.value !== cleanedValue) {
      field.value = cleanedValue;
    }
  });

  field.addEventListener("paste", (event) => {
    const pastedText = event.clipboardData?.getData("text") ?? "";
    const cleanedText = cleanValue(pastedText);

    if (pastedText !== cleanedText) {
      event.preventDefault();
      insertCleanedText(field, cleanedText);
    }
  });

  field.addEventListener("drop", (event) => {
    event.preventDefault();
  });
};

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(nameFieldSelector).forEach((field) => {
    attachFilteredField(field, (character) => allowedNameCharacters.test(character), cleanNameValue);
  });

  document.querySelectorAll(ageFieldSelector).forEach((field) => {
    attachFilteredField(field, (character) => /\d/.test(character), cleanAgeValue);
  });

  document.querySelectorAll(phoneFieldSelector).forEach((field) => {
    attachFilteredField(field, (character) => /[0-9 +().-]/.test(character), cleanPhoneValue);
  });
});
