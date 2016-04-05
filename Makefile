all: test

test:
	phpunit --verbose tests/XoxzoClientTest.php
