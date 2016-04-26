all: test

test:
	phpunit --verbose tests/XoxzoClientTest.php

doc:
	grip README.md
