This repo is to demonstrate that the behaviour between Mockery + mock with expectations + dataProvider fails.

Run the tests with

```
php vendor/bin/phpunit tests
```

That will fail because of the `tests/unit/WithMockeryTest.php` having more than one case in the dataProvider method.
2 other tests classes have been added with the same logic as `tests/unit/WithMockeryTest.php` but using Prophecy and PhpUnit mocks. And these don't fail.

If you remove any case from the array within the `myDataProvider` method in `tests/unit/WithMockeryTest.php` you'll see that the test works fine. This is because there seems to be a bug on Mockery and its internal container.


I submitted an issue to their repo here: https://github.com/mockery/mockery/issues/1193 
