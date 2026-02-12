<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SAMU / nora</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&family=Noto+Sans+JP:wght@300;400;500;700&family=Noto+Sans:wght@300;400;600&family=Playfair+Display:wght@400;600;700&family=Zen+Old+Mincho:wght@400;500&display=swap" rel="stylesheet">

        <style>
            :root {
                --bg-cream: #fffff7;
                --bg-beige: #efe9dd;
                --bg-tan: #d7c6b6;
                --brand-brown: #8d5e2e;
                --brand-deep: #500e00;
                --brand-wine: #5e2418;
                --text-brown: #9b7245;
                --accent-red: #d40000;
                --shadow-soft: 0 0 12px rgba(0, 0, 0, 0.12);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                background: var(--bg-cream);
                color: #000;
                font-family: "Noto Sans JP", sans-serif;
            }

            img {
                display: block;
                max-width: 100%;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .container {
                margin: 0 auto;
                max-width: 1200px;
                padding: 0 24px;
            }

            .section {
                padding: 72px 0;
            }

            .section-title {
                text-align: center;
                margin-bottom: 48px;
            }

            .section-title .en {
                font-family: "Playfair Display", serif;
                font-size: 48px;
                letter-spacing: 1.6px;
                line-height: 1.2;
            }

            .section-title .jp {
                font-family: "Zen Old Mincho", serif;
                font-size: 24px;
                letter-spacing: 0.48px;
                margin-top: 8px;
            }

            .header {
                background: url("https://www.figma.com/api/mcp/asset/f5236c60-628b-4bd8-8cf5-ab93ad2835d4") center / cover no-repeat;
                min-height: 700px;
                position: relative;
            }

            .header-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 32px 0 16px;
            }

            .logo {
                width: 195px;
            }

            .nav-links {
                display: flex;
                align-items: center;
                gap: 28px;
                font-family: "Playfair Display", serif;
                font-size: 20px;
                letter-spacing: 0.48px;
            }

            .nav-links a {
                position: relative;
                padding-bottom: 4px;
            }

            .nav-links a::after {
                content: "";
                position: absolute;
                left: 0;
                bottom: 0;
                width: 100%;
                height: 1px;
                background: #000;
                opacity: 0;
                transition: opacity 0.2s ease;
            }

            .nav-links a:hover::after {
                opacity: 1;
            }

            .shop-btn {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: var(--brand-wine);
                color: #fff;
                padding: 8px 16px;
                border-radius: 8px;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
                font-family: "Playfair Display", serif;
                font-size: 16px;
                text-transform: lowercase;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .shop-btn:hover {
                transform: translateY(-1px);
                box-shadow: var(--shadow-soft);
            }

            .hero {
                max-width: 560px;
                padding: 120px 0 80px;
            }

            .hero-text {
                font-family: "Zen Old Mincho", serif;
                font-size: 36px;
                line-height: 1.6;
            }

            .hero-text .brand {
                font-family: "Playfair Display", serif;
                font-weight: 600;
                font-size: 49px;
                margin: 0 6px;
            }

            .about {
                background: var(--bg-cream);
                position: relative;
                overflow: hidden;
            }

            .about-layout {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 48px;
                align-items: center;
            }

            .about-left {
                position: relative;
                min-height: 420px;
            }

            .about-left img {
                transform: rotate(90deg);
                width: 420px;
                height: 420px;
                object-fit: cover;
                position: absolute;
                left: -40px;
                top: 40px;
            }

            .about-right {
                display: grid;
                gap: 24px;
            }

            .about-right .image {
                width: 100%;
                max-width: 360px;
                justify-self: end;
            }

            .about-copy {
                font-family: "Inter", "Noto Sans JP", sans-serif;
                font-size: 24px;
                line-height: 2.4;
                color: var(--text-brown);
                text-align: center;
            }

            .products {
                background: var(--bg-beige);
            }

            .products-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 40px;
                align-items: start;
            }

            .product-card {
                background: transparent;
                padding: 16px 0;
            }

            .product-tag {
                display: inline-block;
                border: 1px solid var(--brand-brown);
                color: var(--brand-brown);
                padding: 6px 16px;
                font-size: 14px;
                letter-spacing: 2px;
                margin-bottom: 16px;
            }

            .product-title {
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 500;
                font-size: 20px;
                color: var(--brand-deep);
                letter-spacing: -1px;
                margin: 0 0 12px;
            }

            .product-desc {
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 300;
                font-size: 16px;
                color: var(--brand-deep);
                line-height: 1.8;
                margin-bottom: 12px;
            }

            .product-volume {
                font-family: "Inter", "Noto Sans JP", sans-serif;
                font-size: 14px;
                color: var(--brand-brown);
            }

            .product-image-stack {
                display: grid;
                gap: 24px;
            }

            .product-image-stack img {
                width: 100%;
                height: 220px;
                object-fit: cover;
            }

            .howto {
                background: var(--bg-tan);
            }

            .steps {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 24px;
                align-items: start;
            }

            .step-card {
                text-align: center;
                position: relative;
            }

            .step-card img {
                width: 100%;
                height: 260px;
                object-fit: cover;
            }

            .step-label {
                position: absolute;
                top: 0;
                left: 0;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                padding: 10px 14px;
                font-family: "Playfair Display", serif;
                font-weight: 700;
                letter-spacing: 2px;
                text-align: left;
            }

            .step-title {
                font-family: "Zen Old Mincho", serif;
                font-size: 30px;
                margin: 12px 0 8px;
            }

            .step-desc {
                font-family: "Noto Sans JP", sans-serif;
                font-size: 16px;
                line-height: 1.6;
            }

            .special {
                background: var(--bg-beige);
            }

            .special-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 48px;
                align-items: center;
            }

            .special-left {
                position: relative;
                min-height: 460px;
            }

            .special-left .sale-bg {
                position: absolute;
                background: rgba(255, 255, 247, 0.6);
                width: 68%;
                height: 300px;
                left: 0;
                top: 120px;
            }

            .special-left img.product {
                position: relative;
                width: 100%;
                max-width: 520px;
                margin-left: 60px;
                top: 140px;
            }

            .special-left .sale-badge {
                position: absolute;
                top: 60px;
                left: 32px;
                width: 160px;
            }

            .special-left .sale-text {
                position: absolute;
                top: 88px;
                left: 60px;
                text-align: center;
                color: #fff;
                font-weight: 700;
                font-family: "Noto Sans JP", sans-serif;
            }

            .special-left .sale-text .num {
                font-size: 64px;
                line-height: 1;
            }

            .special-left .sale-text .off {
                font-size: 32px;
                line-height: 1.2;
            }

            .special-right {
                text-align: center;
            }

            .special-note {
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 700;
                color: var(--brand-brown);
                font-size: 24px;
                margin-bottom: 12px;
            }

            .special-title {
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 700;
                font-size: 52px;
                margin: 0 0 8px;
            }

            .special-sub {
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 500;
                font-size: 18px;
                margin-bottom: 20px;
            }

            .special-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: center;
                margin-bottom: 24px;
            }

            .special-tag {
                border: 2px solid var(--brand-brown);
                color: var(--brand-brown);
                padding: 8px 24px;
                border-radius: 999px;
                font-size: 16px;
                font-weight: 500;
            }

            .special-price {
                display: flex;
                justify-content: center;
                gap: 16px;
                align-items: baseline;
                margin-bottom: 24px;
            }

            .special-price .old {
                color: var(--brand-brown);
                font-size: 28px;
                text-decoration: line-through;
            }

            .special-price .new {
                color: var(--accent-red);
                font-size: 64px;
                font-family: "Inter", sans-serif;
                letter-spacing: 2px;
            }

            .cta {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                background: var(--brand-brown);
                color: #fff;
                padding: 18px 40px;
                font-size: 22px;
                font-family: "Noto Sans JP", sans-serif;
                font-weight: 600;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .cta:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-soft);
            }

            .footer {
                background: var(--bg-tan);
                padding: 56px 0 24px;
                position: relative;
            }

            .footer-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 24px;
            }

            .footer-links {
                font-family: "Playfair Display", serif;
                font-size: 16px;
            }

            .footer-links span {
                margin: 0 12px;
            }

            .footer-social {
                display: flex;
                gap: 16px;
            }

            .footer-copy {
                text-align: center;
                color: var(--brand-brown);
                margin-top: 32px;
                letter-spacing: 1.4px;
                font-size: 14px;
            }

            .page-top {
                position: absolute;
                right: 60px;
                top: 20px;
                width: 120px;
                text-align: center;
                color: #fff;
            }

            .page-top img {
                width: 100%;
            }

            .page-top span {
                position: absolute;
                top: 48px;
                left: 0;
                right: 0;
                font-family: "Playfair Display", serif;
                font-size: 20px;
            }

            .page-top .arrow {
                position: absolute;
                top: 24px;
                left: 0;
                right: 0;
                margin: 0 auto;
                width: 42px;
            }

            @media (max-width: 1024px) {
                .nav-links {
                    gap: 16px;
                    font-size: 18px;
                }

                .about-layout,
                .products-layout,
                .special-layout {
                    grid-template-columns: 1fr;
                }

                .about-left img {
                    position: static;
                    transform: none;
                    width: 100%;
                    height: 320px;
                }

                .about-right .image {
                    justify-self: center;
                }

                .product-image-stack img {
                    height: 200px;
                }

                .steps {
                    grid-template-columns: 1fr;
                }

                .special-left {
                    min-height: 380px;
                }

                .special-left img.product {
                    margin-left: 0;
                    top: 120px;
                }

                .special-left .sale-bg {
                    width: 100%;
                }
            }

            @media (max-width: 768px) {
                .header {
                    min-height: auto;
                }

                .header-nav {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 16px;
                }

                .nav-links {
                    flex-wrap: wrap;
                }

                .hero {
                    padding: 80px 0 48px;
                }

                .hero-text {
                    font-size: 28px;
                }

                .section {
                    padding: 56px 0;
                }

                .special-title {
                    font-size: 40px;
                }

                .special-price .new {
                    font-size: 48px;
                }

                .footer-top {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }

                .page-top {
                    position: static;
                    margin: 24px auto 0;
                }
            }
        </style>
    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="header-nav">
                    <img class="logo" src="https://www.figma.com/api/mcp/asset/b796b1ef-2636-4de2-ac5c-aabae5be64a4" alt="SAMU">
                    <nav class="nav-links">
                        <a href="#about">About</a>
                        <a href="#products">Products</a>
                        <a href="#howto">How to use</a>
                        <a href="#offer">Special offer</a>
                        <a class="shop-btn" href="#">
                            <img src="https://www.figma.com/api/mcp/asset/92ea91aa-0e35-49c5-97b0-99510797cda3" alt="" width="20" height="20">
                            shop
                        </a>
                    </nav>
                </div>
                <div class="hero">
                    <div class="hero-text">
                        オーガニック素材100%で<br>
                        肌も体も健やかに<br>
                        スキンケアブランド<span class="brand">nora</span>誕生
                    </div>
                </div>
            </div>
        </header>

        <section id="about" class="section about">
            <div class="container">
                <div class="section-title">
                    <div class="en">About</div>
                    <div class="jp">SAMUについて</div>
                </div>
                <div class="about-layout">
                    <div class="about-left">
                        <img src="https://www.figma.com/api/mcp/asset/df2dc308-32f6-4126-b0b6-4591ede6d9b9" alt="">
                    </div>
                    <div class="about-right">
                        <img class="image" src="https://www.figma.com/api/mcp/asset/8629df11-6bcf-4281-8129-3b6afe369b0f" alt="">
                        <div class="about-copy">
                            美しい肌に本当に必要なものは、素肌が知っている。<br>
                            SAMUは、肌が本当に求めるものだけを<br>
                            補うことで肌や身体をすこやかに美しく保つ<br>
                            オーガニック美容をおすすめしています。<br>
                            自然の恵みをたくさん詰め込んだ<br>
                            オーガニックの宝石のような<br>
                            スキンケア商品を研究開発しています。<br>
                            健やかな商品で、健やかな未来を。
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="products" class="section products">
            <div class="container">
                <div class="section-title">
                    <div class="en">Products</div>
                    <div class="jp">SAMUについて</div>
                </div>
                <div class="products-layout">
                    <div>
                        <div class="product-card">
                            <span class="product-tag">化粧水</span>
                            <div class="product-title">nora ブライトニング・ローション</div>
                            <div class="product-desc">
                                うるおいを浸透させる、敏感肌向け化粧水<br>
                                季節の変わり目などの乾燥・敏感肌に浸透して守ります
                            </div>
                            <div class="product-volume">内容量 : 200cc</div>
                        </div>
                        <div class="product-card">
                            <span class="product-tag">化粧水</span>
                            <div class="product-title">nora ブライトニング・ローション</div>
                            <div class="product-desc">
                                うるおいを浸透させる、敏感肌向け化粧水<br>
                                季節の変わり目などの乾燥・敏感肌に浸透して守ります
                            </div>
                            <div class="product-volume">内容量 : 200cc</div>
                        </div>
                        <div class="product-card">
                            <span class="product-tag">化粧水</span>
                            <div class="product-title">nora ブライトニング・ローション</div>
                            <div class="product-desc">
                                うるおいを浸透させる、敏感肌向け化粧水<br>
                                季節の変わり目などの乾燥・敏感肌に浸透して守ります
                            </div>
                            <div class="product-volume">内容量 : 200cc</div>
                        </div>
                    </div>
                    <div class="product-image-stack">
                        <img src="https://www.figma.com/api/mcp/asset/a2a7ab3e-0431-4f1c-b71c-b727fd462396" alt="">
                        <img src="https://www.figma.com/api/mcp/asset/6f90aa17-5b65-49c8-9d8f-a2d97029ec44" alt="">
                        <img src="https://www.figma.com/api/mcp/asset/efb1c6f6-03c1-4b09-85f3-ac6fb33e7e75" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section id="howto" class="section howto">
            <div class="container">
                <div class="section-title">
                    <div class="en">How to use</div>
                    <div class="jp">使用方法</div>
                </div>
                <div class="steps">
                    <div class="step-card">
                        <img src="https://www.figma.com/api/mcp/asset/515d0caa-1784-433b-8211-cbd723e2a682" alt="">
                        <div class="step-label">STEP 01</div>
                        <div class="step-title">潤す</div>
                        <div class="step-desc">2プッシュを乾いた手にとり<br>パッティングで肌に馴染ませます</div>
                    </div>
                    <div class="step-card">
                        <img src="https://www.figma.com/api/mcp/asset/9e4c25cb-4899-49fd-8634-2ca94b221974" alt="">
                        <div class="step-label">STEP 02</div>
                        <div class="step-title">満たす</div>
                        <div class="step-desc">付属のスポイト5cc吸い上げて<br>肌に馴染ませます</div>
                    </div>
                    <div class="step-card">
                        <img src="https://www.figma.com/api/mcp/asset/8c664ecb-2730-43f3-bbac-199e096db5e5" alt="">
                        <div class="step-label">STEP 03</div>
                        <div class="step-title">守る</div>
                        <div class="step-desc">クリームをスパチュラで1スクープとり、<br>肌を守るようになじませます</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="offer" class="section special">
            <div class="container">
                <div class="section-title">
                    <div class="en">Special Offer</div>
                    <div class="jp">初回限定</div>
                </div>
                <div class="special-layout">
                    <div class="special-left">
                        <div class="sale-bg"></div>
                        <img class="product" src="https://www.figma.com/api/mcp/asset/fd9d56e3-4f4c-47a8-adfa-8bef2eec1f38" alt="">
                        <img class="sale-badge" src="https://www.figma.com/api/mcp/asset/6e9fcd58-36bc-4f5c-b8b4-4293caf00b83" alt="">
                        <div class="sale-text">
                            <div class="num">50</div>
                            <div class="off">%</div>
                            <div class="off">off</div>
                        </div>
                    </div>
                    <div class="special-right">
                        <div class="special-note">初めてご購入の方限定</div>
                        <div class="special-title">ファーストnoraセット</div>
                        <div class="special-sub">化粧水＋美容液＋クリーム</div>
                        <div class="special-tags">
                            <span class="special-tag">期間限定販売 3/15〜5/1</span>
                            <span class="special-tag">30日間返品無料キャンペーン</span>
                            <span class="special-tag">送料無料</span>
                        </div>
                        <div class="special-price">
                            <span class="old">¥10,800</span>
                            <span class="new">¥5,400</span>
                        </div>
                        <a class="cta" href="#">
                            初回限定セットを購入する
                            <img src="https://www.figma.com/api/mcp/asset/5500a476-748e-4b2f-88d7-27d109f18f05" alt="" width="18">
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="footer-top">
                    <img class="logo" src="https://www.figma.com/api/mcp/asset/f3e08958-9e12-4c92-85fd-10c2d9ada27c" alt="SAMU">
                    <div class="footer-social">
                        <img src="https://www.figma.com/api/mcp/asset/921d8337-bb1c-451f-b07b-ac078b3a130b" alt="" width="28">
                        <img src="https://www.figma.com/api/mcp/asset/e53a6000-7502-40cb-9a52-d9e8efe87080" alt="" width="28">
                        <img src="https://www.figma.com/api/mcp/asset/55d4319d-0ad4-403a-9f48-05fdbe40fa81" alt="" width="24">
                    </div>
                    <div class="footer-links">
                        Privacy Policy<span>Terms of Use</span>Company<span>Contact</span>
                    </div>
                </div>
                <div class="footer-copy">©SAMU CONMETICS all right reserved</div>
                <a class="page-top" href="#">
                    <img src="https://www.figma.com/api/mcp/asset/76a1b100-acf1-49db-a76d-098190a2df8a" alt="">
                    <img class="arrow" src="https://www.figma.com/api/mcp/asset/2c00b4b2-de61-4359-b17d-dee5cfcf9fc4" alt="">
                    <span>Page Top</span>
                </a>
            </div>
        </footer>
    </body>
</html>
