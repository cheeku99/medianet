# Sample project for media-net recruitment task

### Sample Application Architecture
The sample application is built using core php, to simply present the basic functionality. For simplicity and ease of deployment, an SqlLite database is used.

![N|Solid](http://i.imgur.com/zkL9BZA.png)


### API Endpoints

| Route | Description |
| ------ | ------ |
| /generateToken.php | Return new token(URI) |
|/reports.php | Access stats for all generated tokens |
| /tokenReport.php | Access stats for individual token |
| /getImage.php | Route visited by end clients, returned as a response to [/generateToken.php] [PlMe] |



##### Token Generation
On requesting the token generation API, a new token is generated, saved and a full uri for the image is returned.

```sh
http://<root URL>/getImage.php?token=9a06e70dbad7ab411e28a5b861e56351abf326aa2099984319
```


##### Image URI
The image URI is used within the email, that needs to be tracked. The URI, when hit, receives a request, saves the relevant information and returns an image. 

##### Token Stats
For individual tokens, the data collected is returned in a JSON format
```sh
{
	"c30201a3c37d1b58126cc1f36cfadee20514a1b29a6449d493": [{
		"ID": 4,
		"token": "c30201a3c37d1b58126cc1f36cfadee20514a1b29a6449d493",
		"ipaddress": "127.0.0.1",
		"useragent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36",
		"referer": "NA",
		"otherinformation": "{"USER":"Mohsin","HOME":"/Users/Mohsin","FCGI_ROLE":"RESPONDER","REDIRECT_HANDLER":"php-fastcgi","REDIRECT_STATUS":"200","HTTP_HOST":"media-net.dev","HTTP_CONNECTION":"keep-alive","HTTP_UPGRADE_INSECURE_REQUESTS":"1","HTTP_USER_AGENT":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36","HTTP_ACCEPT":"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8","HTTP_DNT":"1","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_ACCEPT_LANGUAGE":"en-IN,hi-IN;q=0.8,hi;q=0.6,en-GB;q=0.4,en-US;q=0.2,en;q=0.2","PATH":"/Users/Mohsin/Library/Python/2.7/bin:/Users/Mohsin/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/opt/X11/bin:/Applications/Wireshark.app/Contents/MacOS:/Users/Mohsin/.nexustools","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache/2.2.31 (Unix) DAV/2 mod_fastcgi/2.4.6 mod_ssl/2.2.31 OpenSSL/1.0.2j","SERVER_NAME":"media-net.dev","SERVER_ADDR":"127.0.0.1","SERVER_PORT":"80","REMOTE_ADDR":"127.0.0.1","DOCUMENT_ROOT":"/usr/local/var/www/htdocs","SERVER_ADMIN":"you@example.com","SCRIPT_FILENAME":"/Users/Mohsin/Sites/media-net/getImage.php","REMOTE_PORT":"64118","REDIRECT_QUERY_STRING":"token=c30201a3c37d1b58126cc1f36cfadee20514a1b29a6449d493","REDIRECT_URL":"/getImage.php","GATEWAY_INTERFACE":"CGI/1.1","SERVER_PROTOCOL":"HTTP/1.1","REQUEST_METHOD":"GET","QUERY_STRING":"token=c30201a3c37d1b58126cc1f36cfadee20514a1b29a6449d493","REQUEST_URI":"/getImage.php?token=c30201a3c37d1b58126cc1f36cfadee20514a1b29a6449d493","SCRIPT_NAME":"/getImage.php","ORIG_SCRIPT_FILENAME":"/php-fpm","ORIG_PATH_INFO":"/getImage.php","ORIG_PATH_TRANSLATED":"/Users/Mohsin/Sites/media-net/getImage.php","ORIG_SCRIPT_NAME":"/fastcgiphp","PHP_SELF":"/getImage.php","REQUEST_TIME_FLOAT":1498976253.7516,"REQUEST_TIME":1498976253}",
		"createdon": "2017-07-02 06:17:33"
	}]
}
```

### Proposed Application Architecture

For a production ready application, a Laravel PHP Framework can be used to create the REST API and (if needed) a frontend web application for the clients to be able to login, generate URIs, check reports etc.

  - [JSON Web Tokens](https://github.com/tymondesigns/jwt-auth) can be used to implement the entire token logic, for authentication and maintaining tokens against the URIs
  - [Dingo API](https://github.com/dingo/api) for easier Route Management and API Versioning

Since, most of the information generated and stored are specific to individual trackers and more of non-relational in nature (except for the case when tokens generated need to be mapped to a user account), this is a very good use case for a NoSQL or JSON only storage structure. One proposition is to use a MongoDB in production for easier and heavy writes of the application, in addition to keep it fast and scalable.

Alternatively, a complete Redis DB can also be used, to store tokens(URIs) and information related to open events. Even before scaling, a redis only DB would be much faster. **An Apache benchmark with 100 parallel clients issuing 100000 requests measured the average response to about 2-3 milliseconds.** With the use of Redis Clusters, this can be scaled very easily. 

In order to display and update the stats in realtime, **queued jobs with node+socketio** can be used.  The full architecture could look like:

![N|Solid](http://i.imgur.com/D1EMNqO.png)