document.addEventListener('DOMContentLoaded', function () {
    const changeColorButton = document.getElementById('changeColor');
  
    changeColorButton.addEventListener('click', function () {
      const colorInput = document.getElementById('color').value;
      chrome.tabs.query({ active: true, currentWindow: true }, function (tabs) {
        chrome.tabs.sendMessage(tabs[0].id, { color: colorInput });
      });
    });
  });