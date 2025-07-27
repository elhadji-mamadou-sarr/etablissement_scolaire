<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf
    'public_path' => null,  // Override the public path if needed

    /*
    |--------------------------------------------------------------------------
    | Defines
    |--------------------------------------------------------------------------
    |
    | Define the DomPDF environment
    |
    */
    'defines' => [
        /**
         * The location of the DOMPDF font directory
         *
         * The location of the directory containing the fonts used by dompdf.
         * This is normally dompdf/lib/fonts.  This directory must exist and be
         * writable by the webserver process.
         *
         * @var string
         */
        "font_dir" => storage_path('fonts/'), // advised by dompdf (https://github.com/dompdf/dompdf/pull/782)

        /**
         * The location of the DOMPDF font cache directory
         *
         * This directory contains the cached font metrics for the fonts used by
         * dompdf.  This directory can be the same as DOMPDF_FONT_DIR
         *
         * @var string
         */
        "font_cache" => storage_path('fonts/'),

        /**
         * The location of a temporary directory.
         *
         * The directory specified must be writeable by the webserver process.
         * The temporary directory is required to download remote images and when
         * using the PFDLib back end.
         *
         * @var string
         */
        "temp_dir" => sys_get_temp_dir(),

        /**
         * ==== IMPORTANT ====
         *
         * dompdf's "chroot": Prevents dompdf from accessing system files or other
         * files on the webserver.  All local files opened by dompdf must be in a
         * subdirectory of this directory.  DO NOT set it to '/' since this could
         * allow an attacker to access any file on the server.  This should be an
         * absolute path.
         *
         * @var string
         */
        "chroot" => realpath(base_path()),

        /**
         * Protocol whitelist
         *
         * Protocols and PHP wrappers allowed in URIs, and the validation rules
         * that determine if a resouce may be loaded. Full support is not guaranteed
         * for the protocols/wrappers specified
         * by this array.
         *
         * @var array
         */
        "allowed_protocols" => [
            "file://" => ["rules" => []],
            "http://" => ["rules" => []],
            "https://" => ["rules" => []]
        ],

        /**
         * @var string
         */
        "log_output_file" => null,

        /**
         * HTML target media view which should be rendered into pdf.
         * List of types and parsing rules for future extensions:
         * http://www.w3.org/TR/REC-html40/types.html
         *   screen, tty, tv, projection, handheld, print, braille, aural, all
         * Note: aural is deprecated in CSS 2.1 because it is replaced by speech in CSS 3.
         * Note, even though the generated pdf file is intended for print output,
         * the desired content might be different (e.g. screen or projection view of html file).
         * Therefore allow specification of content here.
         *
         * @var string, 'print' or 'screen' or 'projection' or ...
         */
        "default_media_type" => "screen",

        /**
         * The default paper size.
         *
         * North America standard is "letter"; other countries generally "a4"
         *
         * @var string
         */
        "default_paper_size" => "a4",

        /**
         * The default paper orientation.
         *
         * The orientation of the page (portrait or landscape).
         *
         * @var string
         */
        "default_paper_orientation" => "portrait",

        /**
         * The default font family
         *
         * Used if no suitable fonts can be found. This must exist in the font folder.
         * @var string
         */
        "default_font" => "serif", // must be a key in fonts

        /**
         * Image DPI setting
         *
         * This setting determines the default DPI setting for images and fonts.  The
         * DPI may be overridden for inline images by explictly setting the
         * image's width and height style attributes (i.e. if the image's native
         * width is 600 pixels and you specify the image's width as 72 points,
         * the image will have a DPI of 600 in whatever format the image is
         * rendered).
         *
         * The DPI of background images can not be overridden and is controlled by
         * this setting.
         *
         * The DPI is also used in the size calculation for the @page CSS at-rule.
         * It is recommended to set this to 72 or 96 DPI.
         *
         * @var int
         */
        "dpi" => 96,

        /**
         * Enable embedded PHP
         *
         * If this setting is set to true then DOMPDF will automatically evaluate
         * embedded PHP contained within <script type="text/php"> ... </script> tags.
         *
         * Enabling this for documents you do not trust (e.g. arbitrary remote html
         * pages) is a security risk.  Set this option to false if you wish to process
         * untrusted documents.
         *
         * @var bool
         */
        "enable_php" => false,

        /**
         * Enable inline Javascript
         *
         * If this setting is set to true then DOMPDF will automatically insert
         * JavaScript code contained within <script type="text/javascript"> ... </script> tags.
         *
         * @var bool
         */
        "enable_javascript" => false,

        /**
         * Enable remote file access
         *
         * If this setting is set to true, DOMPDF will access remote sites for
         * images and CSS files as required.
         * This is required for part of test case www/test/image_variants.html through www/examples.php
         *
         * Attention: This can be a security risk, in particular in combination with DOMPDF_ENABLE_PHP and
         * allowing remote access to dompdf.php or on allowing remote html code to be passed to
         * $dompdf = new DOMPDF(, $dompdf->load_html(...,
         * This allows anonymous users to download legally doubtful internet content which on
         * tracing back appears to being downloaded by your server, or allows malicious php code
         * in remote html pages to be executed by your server with your account privileges.
         *
         * @var bool
         */
        "enable_remote" => true,

        /**
         * A ratio applied to the fonts height to be more like browsers' line height
         */
        "font_height_ratio" => 1.1,

        /**
         * Use the HTML5 Lib parser
         *
         * Recommended, but not yet used by default for backwards compatibility
         * @var bool
         */
        "enable_html5_parser" => true,
    ],
];