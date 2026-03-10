<?php
$currentYear = (int) date('Y');
$yearsSinceProposal = $currentYear - 1989;
$yearsSinceFirstSite = $currentYear - 1991;
$yearsSinceW3C = $currentYear - 1994;

$webFacts = [
    'The first website was published at CERN.',
    'HTML is a markup language, not a programming language.',
    'The World Wide Web and the Internet are not the same thing.',
    'Tim Berners-Lee also wrote the first web browser/editor.',
    'The W3C was founded to develop open web standards.'
];

$factIndex = date('z') % count($webFacts);
$factOfTheDay = $webFacts[$factIndex];

print '
<section class="home-section">
    <div class="home-container">

        <div class="home-header">
            <h1>HTML5 - A vocabulary and associated APIs for HTML and XHTML</h1>
            <p class="home-subtitle">Learn more about Tim Berners-Lee and the origins of the Web</p>
        </div>

        <div class="home-stats">
            <div class="home-stat-card">
                <span class="home-stat-number">'. $yearsSinceProposal.'</span>
                <span class="home-stat-label">years since the Web proposal</span>
            </div>
            <div class="home-stat-card">
                <span class="home-stat-number">'. $yearsSinceFirstSite.'</span>
                <span class="home-stat-label">years since the first website</span>
            </div>
            <div class="home-stat-card">
                <span class="home-stat-number">'. $yearsSinceW3C.'</span>
                <span class="home-stat-label">years since W3C was founded</span>
            </div>
        </div>

        <div class="home-fact-box">
           <strong>Web fact of the day:</strong> '.$factOfTheDay.'
        </div>

        <div class="home-hero">
            <div class="home-image-card">
                <figure class="home-figure">
                    <img src="img/html5.jpg" alt="HTML5" title="HTML5">
                    <figcaption>
                        Sir Timothy John Berners-Lee OM KBE FRS FREng FRSA FBCS
                        (<time datetime="1955-06-08">born 8 June 1955</time>),
                        also known as TimBL, is an English engineer and computer scientist,
                        best known as the inventor of the World Wide Web.
                    </figcaption>
                </figure>
            </div>

            <div class="home-intro-card">
                <h2>Who is Tim Berners-Lee?</h2>
                <p>Berners-Lee is a British computer scientist who invented the World Wide Web.</p>
                <p>Timothy John Berners-Lee was born on 8 June 1955 and grew up in London. He studied physics at Oxford University and became a software engineer.</p>
                <p>In 1980, while working at CERN, he first described the concept of a global system based on hypertext that would allow researchers anywhere to share information.</p>
            </div>
        </div>

        <div class="home-content-card">
            <div class="home-content">
                <p>
                    In 1984, Berners-Lee returned to CERN, which was also home to a major European Internet node.
                    In 1989, he published a paper called <em>Information Management: A Proposal</em> in which he combined hypertext with the Internet to create a global information-sharing system.
                    He named it the World Wide Web.
                </p>

                <p>
                    He also created the first web browser and editor. The world\'s first website,
                    <strong>info.cern.ch</strong>, was launched on <time datetime="1991-08-06">6 August 1991</time>.
                    It explained the World Wide Web concept and gave users an introduction to building their own websites.
                </p>

                <p>
                    In 1994, Berners-Lee founded the World Wide Web Consortium (W3C) at the Laboratory of Computer Science (LCS) at the Massachusetts Institute of Technology in Boston.
                    He has served as director of the consortium ever since.
                </p>
            </div>

            <div class="home-timeline">
                <h3>Quick timeline</h3>
                <ul>
                    <li><strong>1980</strong> – Enquire prototype at CERN</li>
                    <li><strong>1989</strong> – Web proposal published</li>
                    <li><strong>1991</strong> – First website launched</li>
                    <li><strong>1994</strong> – W3C founded</li>
                </ul>
            </div>

            <div class="home-footer">
                <p>
                    Source:
                    <a href="https://www.bbc.co.uk/history/historic_figures/berners_lee_tim.shtml" target="_blank" rel="noopener noreferrer">BBC</a>
                </p>

                <div class="home-social">
                    <span>Social media:</span>
                    <a href="https://www.linkedin.com/in/tim-berners-lee-b41808ab/" target="_blank" rel="noopener noreferrer">
                        <img src="img/linkedin.svg" alt="LinkedIn" title="LinkedIn">
                    </a>
                    <a href="https://twitter.com/timberners_lee" target="_blank" rel="noopener noreferrer">
                        <img src="img/twitter.svg" alt="Twitter" title="Twitter">
                    </a>
                    <a href="https://plus.google.com/113372414904624897851" target="_blank" rel="noopener noreferrer">
                        <img src="img/google+.svg" alt="Google+" title="Google+">
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>';
?>