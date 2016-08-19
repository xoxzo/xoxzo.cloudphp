all: test

test:
	phpunit --verbose tests/XoxzoClientTest.php

test_din:
	phpunit --filter test_din XoxzoClientTest tests/XoxzoClientTest.php

doc:
	grip README.md
