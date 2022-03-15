class Bridge {
    queryBucketAndBlog() {
        const bucket = document.head.querySelector('[name=current-user-current-bucket-id]').content;
        const blog = document.head.querySelector('[name=current-user-blog-id]').content;

        return { bucket, blog };
    }

    queryBucketAndRecording() {
        const bucket = document.head.querySelector('[name=current-user-current-bucket-id]').content;
        const recording = document.head.querySelector('[name=current-recording-id]').content;

        return { bucket, recording };
    }
}

window.TurboRamBridge = new Bridge;

export default TurboRamBridge;
