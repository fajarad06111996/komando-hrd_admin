default:
   if (navigator.userAgent.search("Edge") == -1 && navigator.userAgent.search("Chrome") != -1) {
        constraints.video.optional = [{
            sourceId: videoSelect.val()
        }];
   } else if (navigator.userAgent.search("Firefox") != -1) {
        constraints.video.deviceId = {
            exact: videoSelect.val()
        };
   } else if (navigator.userAgent.search("kgl") != -1) {
        // add this line for enable show camera in webview react-native
        // set user agent to navigator.userAgent.search('here')
        constraints.video.optional = [{
            sourceId: videoSelect.val()
        }];
   } else {
        constraints.video.deviceId = videoSelect.val();
   }
break;

Copy and replace code this for enable show camera in react native webview, don't forget add permission camera in androidManifest.xml before,

Change user agent to your set user_agent in webview