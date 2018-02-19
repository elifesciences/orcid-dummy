elifeLibrary {
    def commit
    stage 'Checkout', {
        checkout scm
        commit = elifeGitRevision()
    }

    def image
    stage 'Build image', {
        dockerBuild 'orcid-dummy', commit
        image = DockerImage.elifesciences(this, 'orcid-dummy', commit)
    }

    stage 'Project tests', {
        dockerBuildCi 'orcid-dummy', commit
        // deprecated: remove when dockerProjectTests doesn't need it anymore
        sh 'mkdir build/'
        dockerProjectTests 'orcid-dummy', commit
    }

    elifeMainlineOnly {
        stage 'Push image', {
            image.push()
            image.tag('latest').push()
        }
    }
}
