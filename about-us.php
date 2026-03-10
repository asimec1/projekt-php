<?php
$currentYear = (int) date('Y');
$yearsSinceWeb = $currentYear - 1989;
$yearsSinceFirstBrowser = $currentYear - 1990;
$yearsSinceTuring = $currentYear - 2017;

$facts = [
    'The first website went live in 1991.',
    'The Web and the Internet are not the same thing.',
    'HTML, HTTP and URLs are among the core foundations of the Web.',
    'Open data increases transparency and supports innovation.',
    'Tim Berners-Lee invented the World Wide Web at CERN.'
];

$factOfTheDay = $facts[array_rand($facts)];

print '
<section class="about-section">
    <div class="about-container">

        <div class="about-header">
            <span class="about-label">About Us</span>
            <h1>About Us</h1>
            <p class="about-subtitle">The year open data went worldwide</p>
        </div>

        <div class="about-stats">
            <div class="about-stat-card">
                <span class="about-stat-number">'.$yearsSinceWeb.'</span>
                <span class="about-stat-label">years since the Web was proposed</span>
            </div>
            <div class="about-stat-card">
                <span class="about-stat-number">'.$yearsSinceFirstBrowser.'</span>
                <span class="about-stat-label">years since the first web client and server</span>
            </div>
            <div class="about-stat-card">
                <span class="about-stat-number">'.$yearsSinceTuring.'</span>
                <span class="about-stat-label">years since the Turing Prize award</span>
            </div>
        </div>

        <div class="about-fact-box">
            <strong>Fact of the day:</strong> '.$factOfTheDay.'
        </div>

        <div class="about-hero">
            <div class="about-video-card">
                <video controls preload="metadata" playsinline poster="img/video-cover-sir-tim-berners-lee.jpg">
                    <source src="video/TimBernersLee_2010U-320k.mp4" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
            </div>

            <div class="about-intro-card">
                <h2>Sir Tim Berners-Lee</h2>
                <p>
                    He is the Director of the World Wide Web Consortium (W3C), a Web standards organization founded in 1994 which develops interoperable technologies (specifications, guidelines, software, and tools) to lead the Web to its full potential.
                </p>
                <p>
                    He is a Director of the World Wide Web Foundation which was launched in 2009 to coordinate efforts to further the potential of the Web to benefit humanity.
                </p>
                <p>
                    A graduate of Oxford University, Sir Tim invented the Web while at CERN, the European Particle Physics Laboratory, in 1989. He wrote the first web client and server in 1990. His specifications of URIs, HTTP and HTML were refined as Web technology spread.
                </p>
            </div>
        </div>

        <div class="about-content-card">
            <div class="about-content">
                <p>
                    He is the 3Com Founders Professor of Engineering in the School of Engineering with a joint appointment in the Department of Electrical Engineering and Computer Science at the Laboratory for Computer Science and Artificial Intelligence (CSAIL) at the Massachusetts Institute of Technology (MIT) where he co-leads the Decentralized Information Group (DIG). He is also a Professor in the Computer Science Department at the University of Oxford, UK. He is President of and founded the Open Data Institute in London.
                </p>

                <p>
                    In 2011 he was named to the Board of Trustees of the Ford Foundation, a globally oriented private foundation with the mission of advancing human welfare. He is President of London\'s Open Data Institute.
                </p>

                <p>
                    In 2001 he became a Fellow of the Royal Society. He has been the recipient of several international awards including the Japan Prize, the Prince of Asturias Foundation Prize, the Millennium Technology Prize and Germany\'s Die Quadriga award. In 2004 he was knighted by H.M. Queen Elizabeth and in 2007 he was awarded the Order of Merit. In 2009 he was elected a foreign associate of the National Academy of Sciences. He is the author of "Weaving the Web".
                </p>

                <p>
                    On March 18 2013, Sir Tim, along with Vinton Cerf, Robert Kahn, Louis Pouzin and Marc Andreesen, was awarded the Queen Elizabeth Prize for Engineering for "ground-breaking innovation in engineering that has been of global benefit to humanity."
                </p>

                <p>
                    Sir Tim has promoted open government data globally and spend time fighting for rights such as net neutrality, privacy and the openness of the Web.
                </p>

                <p>
                    On <time datetime="2017-04-04">4 April 2017</time>, Sir Tim was awarded the ACM A.M. Turing Prize for inventing the World Wide Web, the first web browser, and the fundamental protocols and algorithms allowing the Web to scale. The Turing Prize, called the "Nobel Prize of Computing", is considered one of the most prestigious awards in Computer Science.
                </p>
            </div>

            <div class="about-footer">
                <p>
                    Video courtesy of
                    <a href="https://www.ted.com/talks/tim_berners_lee_the_year_open_data_went_worldwide" target="_blank" rel="noopener noreferrer">ted.com</a>
                </p>
            </div>
        </div>

    </div>
</section>';

?>