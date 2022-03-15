class Bridge {
    queryBucketAndBlog() {
        const bucket = document.head.querySelector('[name=current-user-current-bucket-id]').content;
        const blog = document.head.querySelector('[name=current-user-blog-id]').content;

        return { bucket, blog };
    }
}

window.TurboRamBridge = new Bridge;

export default TurboRamBridge;
