<?php
# The file is used to encrypt and decrypt a plain text
# --- ENCRYPTION ---

# the key should be random binary, use scrypt, bcrypt or PBKDF2 to
# convert a string into a key
# key is specified using hexadecimal
//$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

# show key size use either 16, 24 or 32 byte keys for AES-128, 192
# and 256 respectively
// $key_size =  strlen($key);
// echo "Key size: " . $key_size . "\n";

function encrypt($plaintext)
{
	$key = pack('H*', "bcb04b7e1f31ecd8b54763051cef08bc35abe029fdebab5e1d417e2ffb2a00a3");
	# create a random IV to use with CBC encoding
	//$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$iv='';
	for($i=0;$i<16;$i++)
	{
		$iv .= dechex(rand(0,15));
	}

	#echo "IV size: " . $iv_size . "\n";
	#echo "IV: " . $iv . "\n";
	
	# use an explicit encoding for the plain text
	$plaintext_utf8 = utf8_encode($plaintext);

	# creates a cipher text compatible with AES (Rijndael block size = 128)
	# to keep the text confidential 
	# only suitable for encoded input that never ends with value 00h
	# (because of default zero padding)
	//$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
								 //$plaintext_utf8, MCRYPT_MODE_CBC, $iv);
	//$ciphertext = openssl_encrypt($plaintext_utf8, 'aes-256-cbc', $key, true, $iv);
	$ciphertext = $plaintext_utf8;

	# prepend the IV for it to be available for decryption
	$ciphertext = $iv . $ciphertext;

	# encode the resulting cipher text so it can be represented by a string
	$ciphertext_base64 = base64_encode($ciphertext);

	return  $ciphertext_base64;
}

# === WARNING ===

# Resulting cipher text has no integrity or authenticity added
# and is not protected against padding oracle attacks.

# --- DECRYPTION ---

function decrypt($ciphertext_base64)
{
	$ciphertext_dec = base64_decode($ciphertext_base64);

	$key = pack('H*', "bcb04b7e1f31ecd8b54763051cef08bc35abe029fdebab5e1d417e2ffb2a00a3");
	# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
	$iv_size = 16;//openssl_cipher_iv_length('aes-256-cbc');//mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	$iv_dec = substr($ciphertext_dec, 0, $iv_size);

	# retrieves the cipher text (everything except the $iv_size in the front)
	$ciphertext_dec = substr($ciphertext_dec, $iv_size);

	# may remove 00h valued characters from end of plain text
	//$plaintext_utf8_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
										 //$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	//$plaintext_utf8_dec = openssl_decrypt($ciphertext_dec, 'aes-256-cbc', $key, true, $iv_dec);	
	$plaintext_utf8_dec = $ciphertext_dec;

	return  $plaintext_utf8_dec;
}

# Test code
/*
 *$plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";
 *$a = encrypt($plaintext);
 *print $a."<br>";
 *print decrypt($a)."<br>";
 */
?>