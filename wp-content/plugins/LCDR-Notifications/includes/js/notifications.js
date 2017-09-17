navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
  serviceWorkerRegistration.pushManager.getSubscription()
    .then(subscription => {
      if (!subscription) {
        subscribeToPush();
      } else {
        unsubscribeToPush()
        .then(() => subscribeToPush())
      }
    })
});

function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (var i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

function sendEndpoint(endpoint, key, auth) {
  var url = self.location.origin + "/wp-json/notif/v1/subscribe?endpoint=" +
    endpoint +
    "&key=" +
    key +
    "&auth=" +
    auth;
  return fetch(url, {
    method: "POST"
  });
}

function removeEndpoint(endpoint, key, auth) {
  var url = self.location.origin + "/wp-json/notif/v1/subscribe?endpoint=" +
    endpoint +
    "&key=" +
    key +
    "&auth=" +
    auth;
  return fetch(url, {
    method: "DELETE"
  });
}



function subscribeToPush() {
  navigator.serviceWorker.ready.then(serviceWorkerRegistration => {
    serviceWorkerRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(notifications_options.vapid_public)
      })
      .then(sub => sendEndpoint(sub.toJSON().endpoint, sub.toJSON().keys.p256dh, sub.toJSON().keys.auth))
      .then(r => console.log('Push Subscription succesfull'))
      .catch(e => {
        if (Notification.permission === 'denied') {
          console.warn('Permission for Notifications was denied');
        } else {
          console.error('Unable to subscribe to push.', e);
        }
      });
  });
}
