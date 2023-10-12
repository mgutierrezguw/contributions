chrome.runtime.onMessage.addListener(function (request, sender, sendResponse) {
    if (request.color) {
      const style = document.createElement('style');
      style.textContent = `
        .v-navigation-drawer.VNavigationDrawerSidebar {
          background: ${request.color} !important;
        }`;
      document.head.appendChild(style);
    }
  });