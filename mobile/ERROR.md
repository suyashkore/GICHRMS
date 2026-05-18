# Success response / OK
Http Code : 200
Response : 

# Insert Success response / Created
Http Code : 201
Response : 

# Invalid attempt / Unauthorized
Http Code : 401
Response : {
  "status": false,
  "message": "Invalid password",
  "attempts_left": 5
}

# Not found 
Http Code : 404
Response : {
  "status": false,
  "message": "User not found"
}

# Input fields empty / Unprocessable Entity
Http Code : 422
Response : {
  "message": "The device type field is required. (and 1 more error)",
  "errors": {
    "device_type": [
      "The device type field is required."
    ],
    "os_version": [
      "The os version field is required."
    ]
  }
}

# Locked
Http Code : 423
Response : {
  "status": false,
  "message": "Account blocked for 15 minutes"
}

# Internal server error
Http Code : 500
Response : {
  "status": false,
  "message": "Something went wrong",
  "error": "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'mobile' in 'where clause' (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: gicsupportcrm, SQL: select * from `users` where `email` = 90000000 or `mobile` = 90000000 limit 1)"
}
