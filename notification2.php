 <?php
 /*
 Register your app in the FCM Console: https://console.firebase.google.com (add project) 
8  1. Click on the newly added project, in the upper left menu is the "Overview" and Gear Settings. 
9  2. Click on the GEAR settings icon, and then on "Project Settings" 
10  3. In the main screen, click on "Cloud Messaging" 
11  4. Here you will find both your "Server Key" and "SenderID" 
12  5. The "Server Key" is what is used below as the API_ACCESS_KEY 
13  6. The "SenderID" is what your app (on the client phone) will need to generate a 'registration_ID' 
14  7. You now need to register the "senderID" in your developer play console of your app 
15  8. Go to: https://play.google.com, login to manage your app on the playstore 
16  9. Click on the specific app and in the left menu under "Development Tools" click on "Serivces & APIs" 
17  10. Under the Firebase Cloud Messaging (FCM) section, click "Link Sender ID", add your "server key" and click "link".  After it links, your "senderID" will appear in the linked section. 
18  11. In your App, (I use Cordova => phonegap-plugin-push plugin), make a call the FCM server, passing the senderID 
19  12. The client App will receive the "registration_ID". - this is a unique ID that specifically registers the users phone  
20     to receive messages on behalf of your app. 
21  13. Your app will need to pass the 'registration_ID' to your server. In my case, my app passes it via an api call. But  
22     your app could simply pass a silent url call to your server to capture the users 'registration_ID'  
23     (ie: http://yourserver.com/passID.php?userID=jdoe&regID=$registration_ID). 
24  14. Ideally, you will want to store that registration_ID locally on your server (mysql). 
25  15. In the future, when you need to push a notification to that user, call up the stored registration_ID and pass it to 
26      the script below: 
27  
 
28  `NOTE: Once your register your app in the FCM console and retrieve your Server Key and SenderID, it might take 12 to 24 hours  
29  for the IDs to propagate to the FCM messaging servers. I experienced this, minutes after registering my code worked but phone  
30  never received the messages...10 hours later...no changes to my code...phone suddenly started getting the messages. I assume  
31  it was a propagation issue.` 
32  
 
33  ## REGISTERING YOUR APPLE APNs KEY in GOOGLE'S FCM CONSOLE: 
34  
 
35  1. You will need to link the Apple version of your app to your FCM App project. On the Firebase project main page, click add "+ Add another App".  Then "iOS App" 
36  2. After it is linked, in the same section as #4 & #5 above, you will see a section for "iOS Apps" and you should see  
37     your App name listed 
38  3. Here you can add your APNs Auth Key ....OR....add APNs Certificates - it is easier and recommend to do the Auth Key method. 
39  4. Login to your Apple Developer Account: https://developer.apple.com/ 
40  5. Click on "Account" , then on "Certificates, IDs & Profiles" 
41  6. Make certain your on the "ALL" section in the left hand menu, the click the (+) sign in the top right of the main screen 
42  7. Under the "Production" section, select the "Apple Push Notification Authentication Key (Sandbox & Production) option. 
43  8. Click on continue 
44  9. You will be provided with an Auth Key AND a downloadable authkey file - you will need both. Download the file - but 
45     do NOT change the name of the file 
46  10. While here copy down your Team ID - click on "Account" again, then on "Membership" in the left hand menu 
47  11. Copy your "Team ID" 
48  12. Back in the FCM Console (#3), click on the "add/upload" button in the Apple APNs section next to your listed app. 
49  13. KeyID = #9 Auth Key, File = "#9 downloaded file", App ID Prefix = "#11 Team ID" 
50  14. Save/upload 
51  
 
52  `You iOS app is now registered with Googles FCM, Google FCM can now send/relay messages to iPhones/iPads 
53  The only remaining step is to have your client iOS/iPhone App retrieve and send a proper registrationID to your server  
54  side script (below). In my case, the same Cordova phonegap-plugin-push plugin extracts the Apple registrationID too.` 
55  
 
5 // 
5 // PHP SERVER SIDE CODE BELOW 
5 // 
5 
 
6 <?php 
*/

  // API access key from Google FCM App Console 
  define( 'API_ACCESS_KEY', 'AAAAKDzdZ4Y:APA91bEN7tdRBlJ34XoEAGc86RI8tHHnv59cyAEtqTEC8w-szc5A8y-gy6W6WcuaV2PZrqENsDXHUNxWi63cpKYaToYps36EjkhyFF4UTPgjJn9BHwxmgIXttKsRocGRnktfE1X7RK--' ); 
  

  // generated via the cordova phonegap-plugin-push using "senderID" (found in FCM App Console) 
  // this was generated from my phone and outputted via a console.log() in the function that calls the plugin 
  // my phone, using my FCM senderID, to generate the following registrationId  
  $singleID = $_POST["token"];
  /*
  $registrationIDs = array( 
       "c7W7oNp89N4:APA91bGSSDe14MrXkcxXvYC_IT-b9tF7sjzPblI41dyNfAx_wcRQ_PG8OiuAtmmsmegj-wa-_nF-o79Q2_DUdyitJyxwoB0xZDmE4peYDWoE7kkkcdOGm56U8cTiE-4t7ML4G4cfPynfUeIx57zaBQauytSEzfdSDA"
  ) ; 
  */

  // prep the bundle 
  // to see all the options for FCM to/notification payload:  
  // https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support  
  

  // 'vibrate' available in GCM, but not in FCM 
  $fcmMsg = array( 
  	'body' => 'here is a message. message', 
  	'title' => 'This is title #1', 
  	'sound' => "default", 
          'color' => "#203E78"  
  ); 
  // I haven't figured 'color' out yet.   
  // On one phone 'color' was the background color behind the actual app icon.  (ie Samsung Galaxy S5) 
  // On another phone, it was the color of the app icon. (ie: LG K20 Plush) 
  

  // 'to' => $singleID ;  // expecting a single ID 
  // 'registration_ids' => $registrationIDs ;  // expects an array of ids 
  // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high. 
  $fcmFields = array( 
  	'to' => $singleID, 
          'priority' => 'high', 
  	'notification' => $fcmMsg 
  ); 
  

  $headers = array( 
	'Authorization: key=' . API_ACCESS_KEY, 
  	'Content-Type: application/json' 
  ); 
    
  $ch = curl_init(); 
  curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
  curl_setopt( $ch,CURLOPT_POST, true ); 
  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false ); 
  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) ); 
  $result = curl_exec($ch ); 
  curl_close( $ch ); 
  echo $result . "\n\n"; 
  ?> 
