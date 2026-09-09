public static void sendSms() {

        String username = System.getenv("YAS_SMS_USERNAME") != null ? System.getenv("YAS_SMS_USERNAME") : "your_username";
        String password = System.getenv("YAS_SMS_PASSWORD") != null ? System.getenv("YAS_SMS_PASSWORD") : "your_password";


	        String auth = username + ":" + password;

	        String encodedAuth = Base64.getEncoder()
	                .encodeToString(auth.getBytes(StandardCharsets.UTF_8));
	        System.out.println("Basic " + encodedAuth);
	        HttpHeaders headers = new HttpHeaders();
	        headers.set("Authorization", "Basic " + encodedAuth);
	        headers.setContentType(MediaType.APPLICATION_JSON);

	        String body = "{\n" +
	                "  \"from\": \"E-fPublique\",\n" +
	                "  \"to\": \"221774517228\",\n" +
	                "  \"text\": \"Test Waly Diouf yas\"\n" +
	                "}";

	        HttpEntity<String> request = new HttpEntity<>(body, headers);

	        RestTemplate restTemplate = new RestTemplate();

	        try {
	            ResponseEntity<String> response = restTemplate.postForEntity(
	                    "https://api.yasbusiness.sn/sms/1/text/single",
	                    request,
	                    String.class
	            );

	            System.out.println(response.getBody());

	        } catch (Exception e) {
	            System.out.println("ERREUR: " + e.getMessage());
	        }
	    }
	
	    public static String statutSms(String idMessage) {

        String username = System.getenv("YAS_SMS_USERNAME") != null ? System.getenv("YAS_SMS_USERNAME") : "your_username";
        String password = System.getenv("YAS_SMS_PASSWORD") != null ? System.getenv("YAS_SMS_PASSWORD") : "your_password";


	        // 🔐 Basic Auth
	        String auth = username + ":" + password;
	        String encodedAuth = Base64.getEncoder()
	                .encodeToString(auth.getBytes(StandardCharsets.UTF_8));

	        HttpHeaders headers = new HttpHeaders();
	        headers.set("Authorization", "Basic " + encodedAuth);
	        headers.setAccept(java.util.Collections.singletonList(MediaType.APPLICATION_JSON));

	        // 🔹 Encodage du paramètre unique
	       // String encodedData = URLEncoder.encode(innerData, StandardCharsets.UTF_8);
	        // 🔗 URL finale
	        String url = "https://api.yasbusiness.sn/sms/3/logs?messageId=" + idMessage;

	        HttpEntity<Void> request = new HttpEntity<>(headers);

	        RestTemplate restTemplate = new RestTemplate();

	        try {
	            ResponseEntity<String> response = restTemplate.exchange(
	                    url,
	                    HttpMethod.GET,
	                    request,
	                    String.class
	            );
System.out.println("############################ response.getBody() "+response.getBody());
	            return response.getBody();

	        } catch (Exception e) {
	            return "ERREUR: " + e.getMessage();
	        }
	
	    }