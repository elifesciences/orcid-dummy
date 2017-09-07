// TODO: could be separated into `develop` and `master`
// with merge upon passing tests
elifeLibrary {
    stage 'Checkout', {
        checkout scm
    }

    stage 'Project tests', {
        elifeLocalTests "./project_tests.sh", ["build/phpunit.xml"]
    }
}
