var cacheName = 'merajbd-cache-v1.0';
var filesToCache = [
'css/materialize.css',
'css/fix.css',
'css/material-icons.css',
'css/MaterialIcons-Regular.eot',
'css/MaterialIcons-Regular.ttf',
'css/MaterialIcons-Regular.woff',
'css/MaterialIcons-Regular.woff2',
'js/main.js',
'js/materialize.min.js',
'js/url.js',
'images/logo.svg'
];
self.addEventListener('install', function(event) {
event.waitUntil(
caches.open(cacheName)
.then(function(cache) {
return cache.addAll(filesToCache);
})
);
});
self.addEventListener('fetch', function(event) {
event.respondWith(
caches.match(event.request)
.then(function(response) {
if(response){
return response
} else {
var reqCopy = event.request.clone();
return fetch(reqCopy, {credentials: 'include'})
.then(function(response) {
if(!response || response.status !== 200 || response.type !== 'basic') {
return response;
}
var resCopy = response.clone();
caches.open(cacheName)
.then(function(cache) {
return cache.put(reqCopy, resCopy);
});
return response;
})
}
})
);
});
