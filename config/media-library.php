<?php

declare(strict_types=1);

return [
    /*
     * The disk on which to store added files and derived images by default.
     */
    'disk_name' => env('MEDIA_DISK', 'public'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 10, // 10MB

    /*
     * This queue connection will be used to generate derived and responsive images.
     * Leave empty to use the default queue connection.
     */
    'queue_connection_name' => env('QUEUE_CONNECTION', 'sync'),

    /*
     * This queue will be used to generate derived and responsive images.
     * Leave empty to use the default queue.
     */
    'queue_name' => env('MEDIA_QUEUE', ''),

    /*
     * By default all conversions will be performed on a queue.
     */
    'queue_conversions_by_default' => env('QUEUE_CONVERSIONS_BY_DEFAULT', true),

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * The fully qualified class name of the model used for temporary uploads.
     */
    'temporary_upload_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * When enabled, media urls will be signed to ensure they are not modified.
     */
    'enable_temporary_uploads_session_affinity' => true,

    /*
     * When enabled, media uploads will be processed by default.
     */
    'generate_thumbnails_for_temporary_uploads' => true,

    /*
     * This is the class that is responsible for naming generated files.
     */
    'file_namer' => Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer::class,

    /*
     * The class that contains the strategy for determining a media file's path.
     */
    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * Here you can specify which path generator should be used for the given class.
     */
    'custom_path_generators' => [
        // Model::class => PathGenerator::class
        // or
        // 'model_morph_alias' => PathGenerator::class
    ],

    /*
     * When urls to files get generated, this class will be called. Use the default
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    /*
     * Moves media on updating to keep path consistent. Enable it only with a custom
     * PathGenerator that uses media UUID.
     */
    'moves_media_on_update' => false,

    /*
     * Whether to activate versioning when urls to files get generated.
     * When activated, this attaches a ?v=xx query string to the URL.
     */
    'version_urls' => false,

    /*
     * The media library will try to optimize all converted images by removing
     * metadata and applying a little bit of compression. These are
     * the optimizers that will be used by default.
     */
    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '-m85', // Set maximum quality to 85%
            '--force', // Ensure that the file is overwritten
            '--strip-all', // Strip all metadata
            '--all-progressive', // Convert all images to progressive JPEGs
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force', // Overwrite existing files
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0', // Optimize faster
            '-o2', // Set optimization level
            '-quiet', // Quiet mode
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--disable=cleanupIDs', // Do not remove IDs
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b', // Batch mode
            '-O3', // Set optimization level
        ],
        Spatie\ImageOptimizer\Optimizers\Avifenc::class => [
            '-a cq-level=23', // Constant Quality mode
            '-j all', // Use all available CPU cores
            '--min 0', // Minimum quantizer
            '--max 63', // Maximum quantizer
            '--speed 9', // Speed preset (0=slowest, 10=fastest)
        ],
        Spatie\ImageOptimizer\Optimizers\Cwebp::class => [
            '-m 4', // Compression method
            '-pass 10', // Number of passes
            '-mt', // Use multi-threading
            '-q 75', // Quality setting
        ],
    ],

    /*
     * These generators will be used to create an image of media files.
     */
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Avif::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Svg::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Video::class,
    ],

    /*
     * The path where to store temporary files while performing image conversions.
     * If set to null, storage_path('media-library/temp') will be used.
     */
    'temporary_directory_path' => null,

    /*
     * The engine that will be used to create images. Can be either 'gd' or 'imagick'.
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * FFMPEG & FFProbe binaries paths, only used if you try to generate video
     * thumbnails and have installed the php-ffmpeg/php-ffmpeg composer dependency.
     */
    'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    'ffprobe_path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),

    /*
     * Here you can override the class names of the jobs used by this package.
     */
    'jobs' => [
        'perform_conversions' => Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob::class,
        'generate_responsive_images' => Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob::class,
    ],

    /*
     * When using the addMediaFromUrl method you may want to replace the default downloader.
     * This is particularly useful when the url of the image is behind a firewall and
     * need to add additional flags, possibly using curl.
     */
    'media_downloader' => Spatie\MediaLibrary\Downloaders\DefaultDownloader::class,

    'remote' => [
        /*
         * Any extra headers that should be included when uploading media to
         * a remote disk. Even though supported headers may vary between
         * different drivers, a sensible default has been provided.
         *
         * Supported by S3: CacheControl, Expires, StorageClass,
         * ServerSideEncryption, Metadata, ACL, ContentEncoding
         */
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

    'responsive_images' => [
        /*
         * This class is responsible for calculating the target widths of the responsive
         * images. By default we optimize for filesize and create variations that each are 30%
         * smaller than the previous one.
         */
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,

        /*
         * By default rendering media to a responsive image will add some javascript and a tiny placeholder.
         * This ensures that the browser can already determine the correct layout.
         */
        'use_tiny_placeholders' => true,

        /*
         * This class will generate the tiny placeholder used for progressive image loading.
         */
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    /*
     * When converting Media instances to response the media library will add
     * a `loading` attribute to the `img` tag. Here you can set the default value
     * of that attribute.
     *
     * Possible values: 'lazy', 'eager', 'auto' or null if you don't want to set any loading instruction.
     *
     * More info: https://css-tricks.com/native-lazy-loading/
     */
    'default_loading_attribute_value' => null,

    /*
     * You can specify a prefix for that is used for storing all media.
     * If you set this to `/my-subdir`, all your media will be stored in a `/my-subdir` directory.
     */
    'prefix' => env('MEDIA_PREFIX', ''),
];
