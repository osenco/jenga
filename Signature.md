# Why signatures?
To ensure the security of your transactions or requests, we have implemented security controls that we ensure that transactions can only be initiated by you and no one else. To achieve this, we use message signatures.

## How it works
All requests, other than the Identity API, will be required to have a signature in the header. The structure of the header will be as follows:
```cmd
Signature: <Signature in Base64>
```

To generate the signature, you will need create a key pair of private key and public key. You will share the public key with us and use the private key to generate the signature.

## Creating private & public keys
Use following command in command prompt to generate a keypair with a self-signed certificate.

In this command, we are using the openssl. You can use other tools e.g. keytool (ships with JDK - Java Developement Kit), keystore explorer e.t.c

### GENERATE YOUR KEY PAIR
```cmd
openssl genrsa -out privatekey.pem 2048 -nodes
```

Once you are successful with the above command a file (`privatekey.pem`) will be created on your present directory, proceed to export the public key from the keypair generated. The command below shows how to do it.

### NOW EXPORT YOUR PUBLIC KEY
```cmd
openssl rsa -in privatekey.pem -outform PEM -pubout -out publickey.pem
```

If the above command is successful, a new file (`publickey.pem`) will be created on your present directory. Copy the contents of this file and add it on our jengaHQ portal. Make sure to copy only the contents of the keyblock and paste as is.

## The Generated Key Files

The `privatekey.pem` file looks something like this:

### Example Private Key
```
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1BvVbYnuhGGmmIwUdUkFP+WG+tkXyf+o7DopD2MgDh+jwyvA
jwDbSENHOwRuIzYEPBePk1lcchTDraz6VbWbwnDWJNn6cQkDCozRvuN1JnYa88Yy
u7XFQyvskwpk2zgzJ3azuDYAZ0I4yBXAeamLXibOOjm9KFGrBhDMGUtQLVvayZTT
iyyJnDXh5bNISjZeWU1VEiksaMUYujrXmLKDIlFM8xlJJvmvijlwS23J9oP3co3u
Hhd14pGXHKOYXvyVt3Q1taFIps7zS2x2vsGCaK9cdHrExWQdF9fzN95QfagMp7f2
DSMQVhOsXTdZFXMOrkVtWOTlwUJucBGstKOjNwIDAQABAoIBADYvXhh7kgkTgSGb
N2a23rZyBkdyyhb6Tsb6HJ8nrXquLoGfXbOqflo5harX+OLZ278WLcFwpKMoFsz5
UYIvwLitZqdHYCkcKkC5tKNVLApFRaFc0n0NdHUydV8i2pz+AGNmeYbnlLbMPgEv
PVpXK5lDxI8vTNlN86i7Bci4aqULSLYQ9E4/yWOAEAkp9+O7lb6HKcYQ8SgpZ9d9
M0RmxP4Qgc7HdYGo8KzvFJHFtTxOmDMOjWShAxdk77QmnZAznmpmz4v4uUbwR8YK
P7oZV7Lvl1gfma/h+kYR5yd5kJtHSu2+Q2n8gLRfUeFGqD56d1O93VnhSJyhI/OR
zqNhZjECgYEA+zr273pOXDTHRbLVOrYDjxHY6DnD/OeV+qaiCOVFIjTdWAITMuvr
NwKn+Ez0nSBCmKiozdcOzzjxllRs7BhhNGwlQlJloJurfbGIbKb0lSFRQD0bXcxY
32lVscKotMP4lQmmTlALUxbPaCt/MmBsgXg7IA+lGe+I5evAOImE1tUCgYEA2CK6
uUtO6EUBYRC88oyXdlh5lsEM6GpsMHFIlHLeW2EmxTbaaWVf91LRg7lyL+UXEWQ6
zy/oZDuXZI3EVQN2Po2Pcs9V4GFa9FO8YODzdoaHos+f9pe841enZNHkSkXvWXKv
j9HjvVsoKGq3Lg9acLx9dMVmx8ilT1FvDcMz79sCgYBD4soJKgZ0mfpi1hESPU62
4T64eauA8l8vjMlqF/HXbWuGNYFUmDVF9xzGVp0evDHiqGh8vqkMy7lUQtnv7iKO
FM74neVCQe5UF53ipjae+ZLIBfsYHHjDXeY/E3ec6PuJ4kKjFLQKrrY60s4bIb0Q
OxnW7wNQ/84BOvQFEvvnRQKBgQDCwwjf0CzawNPtU9fv+SDDVBa88llfVgcH4A03
OAuG7JSzQiqurts7UzXZLVLoNdgDo/4alWEkcU6LHfS9ZtE2rPmGy67m8tOzN4GZ
CxxYwgGXhODwpOthMat1/m1pQHvebqolP02pZGtbgE5xAwTMcg3bG8byYKwWPZuF
G1HB4QKBgQC5xyHCNq2jQ9YAvja6oqohx59a9Y57R+Xb1Z42w64fouZcbysVWM1f
bfSXnsW49RLDH0Ynns8i5jb+LMdL6W7UujdqrgNMmcNF2GXxHqnYxD10SKRctio5
7gfs5SeS0jvcs7NLCRMhw/yol4pRg12HWcm/YsIpn/na/hUzHesJ+A==
-----END RSA PRIVATE KEY-----
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA1BvVbYnuhGGmmIwUdUkFP+WG+tkXyf+o7DopD2MgDh+jwyvA
jwDbSENHOwRuIzYEPBePk1lcchTDraz6VbWbwnDWJNn6cQkDCozRvuN1JnYa88Yy
u7XFQyvskwpk2zgzJ3azuDYAZ0I4yBXAeamLXibOOjm9KFGrBhDMGUtQLVvayZTT
iyyJnDXh5bNISjZeWU1VEiksaMUYujrXmLKDIlFM8xlJJvmvijlwS23J9oP3co3u
Hhd14pGXHKOYXvyVt3Q1taFIps7zS2x2vsGCaK9cdHrExWQdF9fzN95QfagMp7f2
DSMQVhOsXTdZFXMOrkVtWOTlwUJucBGstKOjNwIDAQABAoIBADYvXhh7kgkTgSGb
N2a23rZyBkdyyhb6Tsb6HJ8nrXquLoGfXbOqflo5harX+OLZ278WLcFwpKMoFsz5
UYIvwLitZqdHYCkcKkC5tKNVLApFRaFc0n0NdHUydV8i2pz+AGNmeYbnlLbMPgEv
PVpXK5lDxI8vTNlN86i7Bci4aqULSLYQ9E4/yWOAEAkp9+O7lb6HKcYQ8SgpZ9d9
M0RmxP4Qgc7HdYGo8KzvFJHFtTxOmDMOjWShAxdk77QmnZAznmpmz4v4uUbwR8YK
P7oZV7Lvl1gfma/h+kYR5yd5kJtHSu2+Q2n8gLRfUeFGqD56d1O93VnhSJyhI/OR
zqNhZjECgYEA+zr273pOXDTHRbLVOrYDjxHY6DnD/OeV+qaiCOVFIjTdWAITMuvr
NwKn+Ez0nSBCmKiozdcOzzjxllRs7BhhNGwlQlJloJurfbGIbKb0lSFRQD0bXcxY
32lVscKotMP4lQmmTlALUxbPaCt/MmBsgXg7IA+lGe+I5evAOImE1tUCgYEA2CK6
uUtO6EUBYRC88oyXdlh5lsEM6GpsMHFIlHLeW2EmxTbaaWVf91LRg7lyL+UXEWQ6
zy/oZDuXZI3EVQN2Po2Pcs9V4GFa9FO8YODzdoaHos+f9pe841enZNHkSkXvWXKv
j9HjvVsoKGq3Lg9acLx9dMVmx8ilT1FvDcMz79sCgYBD4soJKgZ0mfpi1hESPU62
4T64eauA8l8vjMlqF/HXbWuGNYFUmDVF9xzGVp0evDHiqGh8vqkMy7lUQtnv7iKO
FM74neVCQe5UF53ipjae+ZLIBfsYHHjDXeY/E3ec6PuJ4kKjFLQKrrY60s4bIb0Q
OxnW7wNQ/84BOvQFEvvnRQKBgQDCwwjf0CzawNPtU9fv+SDDVBa88llfVgcH4A03
OAuG7JSzQiqurts7UzXZLVLoNdgDo/4alWEkcU6LHfS9ZtE2rPmGy67m8tOzN4GZ
CxxYwgGXhODwpOthMat1/m1pQHvebqolP02pZGtbgE5xAwTMcg3bG8byYKwWPZuF
G1HB4QKBgQC5xyHCNq2jQ9YAvja6oqohx59a9Y57R+Xb1Z42w64fouZcbysVWM1f
bfSXnsW49RLDH0Ynns8i5jb+LMdL6W7UujdqrgNMmcNF2GXxHqnYxD10SKRctio5
7gfs5SeS0jvcs7NLCRMhw/yol4pRg12HWcm/YsIpn/na/hUzHesJ+A==
-----END RSA PRIVATE KEY-----
```

### Example Public Key
```
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1BvVbYnuhGGmmIwUdUkF
P+WG+tkXyf+o7DopD2MgDh+jwyvAjwDbSENHOwRuIzYEPBePk1lcchTDraz6VbWb
wnDWJNn6cQkDCozRvuN1JnYa88Yyu7XFQyvskwpk2zgzJ3azuDYAZ0I4yBXAeamL
XibOOjm9KFGrBhDMGUtQLVvayZTTiyyJnDXh5bNISjZeWU1VEiksaMUYujrXmLKD
IlFM8xlJJvmvijlwS23J9oP3co3uHhd14pGXHKOYXvyVt3Q1taFIps7zS2x2vsGC
aK9cdHrExWQdF9fzN95QfagMp7f2DSMQVhOsXTdZFXMOrkVtWOTlwUJucBGstKOj
NwIDAQAB
-----END PUBLIC KEY-----
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1BvVbYnuhGGmmIwUdUkF
P+WG+tkXyf+o7DopD2MgDh+jwyvAjwDbSENHOwRuIzYEPBePk1lcchTDraz6VbWb
wnDWJNn6cQkDCozRvuN1JnYa88Yyu7XFQyvskwpk2zgzJ3azuDYAZ0I4yBXAeamL
XibOOjm9KFGrBhDMGUtQLVvayZTTiyyJnDXh5bNISjZeWU1VEiksaMUYujrXmLKD
IlFM8xlJJvmvijlwS23J9oP3co3uHhd14pGXHKOYXvyVt3Q1taFIps7zS2x2vsGC
aK9cdHrExWQdF9fzN95QfagMp7f2DSMQVhOsXTdZFXMOrkVtWOTlwUJucBGstKOj
NwIDAQAB
-----END PUBLIC KEY----
```