# Kaltura Generated API Client integration For Backend :-

Following are the steps to follow:

1: run composer install (Reference Link:https://packagist.org/packages/kaltura/api-client-library)

2: Require Kaltura Credentials For Kaltura Session Method ('secret', partnerId, expiry, privileges and sessionType)

3: Kaltura API endpoint = /api/v1/kaltura/get-media-entry-list

4: Update .env like below:

Kaltura_SERVICE_URL='https://www.kaltura.com'
Kaltura_SECRET='secret'
Kaltura_PARTNER_ID='partner-id'
Kaltura_EXPIRY=86400
Kaltura_PRIVILEGES='*'
Kaltura_SESSION_TYPE='2'
