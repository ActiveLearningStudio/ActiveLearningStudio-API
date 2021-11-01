# Kaltura Generated API Client integration For Backend :-

Following are the steps to follow:

1: run composer install (Reference Link:https://packagist.org/packages/kaltura/api-client-library)

2: Require Kaltura Credentials For Kaltura Session Method ('secret', partnerId, expiry, privileges and sessionType)

3: Kaltura API endpoint = /api/v1/kaltura/get-media-entry-list

4: Update .env like below:

Kaltura_SERVICE_URL='https://www.kaltura.com'
Kaltura_SECRET=SECRET_KEY		//Remember to provide the correct secret according to the sessionType you want
Kaltura_PARTNER_ID=PARTNER_ID	
Kaltura_EXPIRY=86400
Kaltura_PRIVILEGES='*'
Kaltura_SESSION_TYPE=SESSION_TYPE	//It may be 0 or 2, 0 for user and 2 for admin (https://www.kaltura.com/api_v3/testmeDoc/  |enums/KalturaSessionType.html)
