<?php

$logged_url = "";

if(LOGGED_IN) {

	$_GET["sivu"] = $_GET["osio"];
	$_GET["osio"] = $_GET["id"];
	$_GET["id"] = $_GET["sub"];
	$logged_url = 'home/';
}

require_once("header_home.php");

?>

<div id="content">

	<div class="inner no-padding<?php echo ($_GET["sivu"] == "gene_analysis") ? ' wide' : ''; ?>">

		<header class="home_header">
			<div class="upper_nav">
				<a href="<?php echo URL; ?>login/"><?php echo (LOGGED_IN) ? "Back" : "Login" ?></a>
			</div>
			<div class="right"></div>
			<a href="<?php echo URL; ?>"><img src="<?php echo URL; ?>images/logo_iso.png" width="300" height="60" class="logo" alt="" />
			<h1> Turku Endometriosis Database </h1></a>
			<div class="clear"></div>
		</header>

		<div class="menu"></div>

		<ul class="nav">

			<li<?php echo (!isset($_GET["sivu"])) ? ' class="selected nav_home"' : ' class="nav_home"'; ?>><a href="<?php echo URL.$logged_url; ?>"><i class="fa fa-home"></i></a></li>
			<li<?php echo ($_GET["sivu"] == "gene_analysis") ? ' class="selected nav_gene_analysis"' : ' class="nav_gene_analysis"'; ?>><a href="<?php echo URL.$logged_url; ?>gene_analysis/"> Start gene analysis </a></li>

			<li<?php echo ($_GET["sivu"] == "research") ? ' class="selected"' : ''; ?>><a href="<?php echo URL.$logged_url; ?>research/"> Research </a></li>
			<li<?php echo ($_GET["sivu"] == "collaboration") ? ' class="selected"' : ''; ?>><a href="<?php echo URL.$logged_url; ?>collaboration/"> Collaboration </a></li>
			<li<?php echo ($_GET["sivu"] == "people") ? ' class="selected"' : ''; ?>><a href="<?php echo URL.$logged_url; ?>people/"> Endomet Team </a></li>
			<li<?php echo ($_GET["sivu"] == "contact") ? ' class="selected"' : ''; ?>><a href="<?php echo URL.$logged_url; ?>contact/"> Contact </a></li>

		</ul>

		<?php if(!isset($_GET["sivu"])) { ?>

		<article class="content">

			<div class="carousel loading">

				<figure class="img_container" style="background-image: url(<?php echo URL; ?>images/karuselli/1.jpg)">
					<h2> Turku Endometriosis Database</h2>
					<p>The Turku Endometriosis database is owned by, The University of Turku and the University of Turku, Turku University Hospital.</p>
				</figure>

				<figure class="img_container" style="background-image: url(<?php echo URL; ?>images/karuselli/2.jpg)">
					<h2> Turku Endometriosis Database</h2>
					<p> An extensive collection of human samples and data from patients, that aids in the research for better diagnosis or cure for endometriosis.  </p>
				</figure>

				<figure class="img_container" style="background-image: url(<?php echo URL; ?>images/karuselli/3.jpg)">
					<h2> Turku Endometriosis Database</h2>
					<p> Our core sector of experts are competent scientists, healthcare professionals with years of experience in medical research. </p>
				</figure>

				<figure class="img_container" style="background-image: url(<?php echo URL; ?>images/karuselli/4.jpg)">
					<h2> Turku Endometriosis Database</h2>
					<p> A platform for data driven solutions in endometriosis diagnosis. </p>
				</figure>

			</div>

			<div class="links">

				<div class="link">
					<a href="<?php echo URL.$logged_url; ?>analysis/"><img src="<?php echo URL; ?>images/nostot/4.jpg" alt="" /></a>
					<h3> Gene analysis </h3>
					<p> Gene expression analysis from 1811 different tissue samples, 242 patients and controls. </p>
				</div>

				<div class="link">
					<a href="<?php echo URL.$logged_url; ?>research/"><img src="<?php echo URL; ?>images/nostot/1.jpg" alt="" /></a>
					<h3> Research </h3>
					<p> Extensive collection of clinical samples for quality endometriosis research. </p>
				</div>

				<div class="link">
					<a href="<?php echo URL.$logged_url; ?>people/"><img src="<?php echo URL; ?>images/nostot/2.jpg" alt="" /></a>
					<h3> People </h3>
					<p> Core sector of competent scientists and healthcare professionals. </p>
				</div>

				<div class="link">
					<a href="<?php echo URL.$logged_url; ?>research/publications/"><img src="<?php echo URL; ?>images/nostot/3.jpg" alt="" /></a>
					<h3> Publications </h3>
					<p> List of endometriosis publications. </p>
				</div>

			</div>

		</article>

			<?php } else if($_GET["sivu"] == "research") { ?>

				<article class="content flex">

					<div class="content_nav">

						<a href="<?php echo URL.$logged_url; ?>research/"<?php echo (!isset($_GET["osio"])) ? ' class="selected"' : ''; ?>> Research </a>
						<a href="<?php echo URL.$logged_url; ?>research/goal/"<?php echo ($_GET["osio"] == "goal") ? ' class="selected"' : ''; ?>> Research goal </a>
						<a href="<?php echo URL.$logged_url; ?>research/patient/"<?php echo ($_GET["osio"] == "patient") ? ' class="selected"' : ''; ?>> Patient recruitment </a>
						<a href="<?php echo URL.$logged_url; ?>research/publications/"<?php echo ($_GET["osio"] == "publications") ? ' class="selected"' : ''; ?>> Publications </a>

					</div>

					<div id="overview" class="content_article">

						<?php if(!isset($_GET["osio"])) { ?>

						<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><span>Research</span></div>

						<h2> Endometriosis </h2>

						<p>Endometriosis is a common gynecological disorders defined by the presence of endometrium-like tissue (epithelium and stroma) outside the uterus. The extrauterine endometrium, associates with chronic inflammation of the peritoneal cavity and causes severe chronic, cyclical pain symptoms and subfertility.</p>

						<img src="<?php echo URL; ?>images/w.jpg" alt="" style="display: block; margin: 0 auto;" />

						<p>Based on their clinical appearance, endometriosis lesions are classified as superficial peritoneal lesions, deep lesions infiltrating into the tissue and ovarian endometriotic cysts (endometriomas). The prevalence of endometriosis is estimated to be about 10% among reproductive-aged women regardless of race or ethnicity, and 35-60% among women with fertility problems. The World Endometriosis Research Foundation estimates that 176 million women suffer from endometriosis globally, but there are significant unmet needs for patients, including delayed diagnosis and a lack of effective medical therapies. Endometriosis is a debilitating disease that severely reduces quality of life. It can have life-long effects on patient relationships, family planning and career. From a societal perspective, the overall cost of endometriosis is high and comparable with other chronic diseases. The aim of our research is to develop better tools to improve the diagnosis and treatment of endometriosis by better understanding of the disease mechanisms.</p>

						<?php } else if($_GET["osio"] == "goal") { ?>

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>research/">Research</a><span class="sep">&raquo;</span><span>Research goal</span></div>

							<h2> Research goal </h2>

							<p><strong>DISEASE MECHANISMS</strong></p>

							<p>The etiology of endometriosis is only partially understood. Endometriosis has an inherited component and is essentially dependent on the presence of estrogen. There are several theories explaining the mechanism of the events leading to the development of endometriosis, and these different theories are all supported by scientific evidence. The pathogenesis of the disease may involve all these mechanisms, and the different endometriosis subtypes may have different mechanism of origin. Endometriosis research is complicated by the lack of practical study models. As a consequence, most research is carried out using primary tissue and cell culture models based on samples from voluntary patients. The amount of sample material available is limited, and thus, intense collaboration is needed between the hospital practicing endometriosis surgery and the laboratory.</p>

							<p>We are continuously carrying out research related to endometriosis pathogenesis, with our major scope being the role of sex steroids and WNT signaling in the pathogenesis and growth of endometriosis. We are developing novel study models to understand endometriosis pathogenesis and recurrence, including immortalized cell lines, and applying large omics-based studies combined with patient questionnaire data, including up to ten years follow-up of the surgically treated patients.</p>

							<p><strong>DIAGNOSTICS</strong></p>

							<p>Endometriosis causes infertility, chronic and cyclical pain with variable severity. These kinds of symptoms, however, are not unique to endometriosis, other diseases have similar symptoms making a reliable diagnosis challenging. Because of the relatively unspecific abdominal and pelvic pain symptoms, and the unrecognized nature of endometriosis, there is a 7 to 10-year delay from the beginning of symptoms to diagnosis. A more definitive diagnosis can be obtained by laparoscopy combined with histopathological confirmation, often preceded by treatment with oral contraceptives and/or NSAIDs. Hence, there are great challenges in the field of endometriosis diagnostics. Especially, as there is a high demand for non-invasive diagnostic assays, for example biomarkers measurable from blood, urine or saliva, potentially aided by symptom-based questionnaires.</p>

							<p>In the field of endometriosis diagnostics, we 1) Search for novel biomarkers measurable from body fluids and 2) Develop machine-learning algorithm based tools for early diagnosis and patient stratification. The symptom-based questionnaires are combined with biomarker data to identify prognostic markers e.g. for patients with high risk for infertility or recurrence.</p>

							<p><strong>TREATMENT</strong></p>

							<p>Currently there are no definitive cure for endometriosis, but different treatment options exist to reduce the severity of the symptoms, and to improve quality of life. Besides NSAIDs, current medical treatments include off-label use of ovary-suppressing therapies, such as contraceptives, progestin and GnRH-analogs that are highly unspecific and preclude pregnancy. These medical therapies are effective only in 50 % of patients, and surgery yields the best treatment outcome. Yet, endometriosis is found only in one third of patients undergoing surgery for endometriosis suspicion. Furthermore, endometriosis has a recurrence rate up to 50 % after surgical removal of the lesions, and there are no means of predicting which patients are at a risk for recurrence. The poorly understood pathogenesis and lack of feasible preclinical models set limitations to the development of novel and targeted therapies.</p>

							<p>We are actively collaborating with industrial partners to enable efficient transfer of our results into drug discovery. Based on our long-lasting studies on steroid hormone action, a potential new treatment for endometriosis, based on inhibition of the HSD17B1 enzyme, is currently in preclinical development by Forendo Pharma.&nbsp;</p>


						<?php } else if($_GET["osio"] == "patient") { ?>

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>research/">Research</a><span class="sep">&raquo;</span><span>Patient recruitment</span></div>

							<h2> Patient recruitment </h2>

							<p>Patient recruitment are carried out at the obstetrics and gynecology units of four hospitals in Finland, the Hospital District of Helsinki and Uusima (HUS) Helsinki, North Karelia Central Hospital and Honkalampi Center Joensuu, Central Finland Health Care District Jyvaskyla, P&auml;ij&auml;t-H&auml;me Hospital Lahti, and the Hospital District of Southwest Finland Turku, and samples collected are stored in collaboration with Auria Biobank.</p>

							<p><strong>Research Ethics</strong></p>

							<p>Our research and clinical sample collection has been approved by the Ethical Committee of the Hospital District of Southwest Finland. A written informed consent is required from all patients prior to taking part in the study and sample collection. All patient data are anonymized and patients may discontinue taking part at any time without reason or repercussion. We mainly use human samples from voluntary donors, but also cell culture and animal experimentation when necessary. Procedures accepted by the National Animal Experiment Board (El&auml;inkoelautakunta, ELLA) are applied for animal experiments. The institutional policies on animal experimentation fully meet the requirements as defined in the NIH Guide on animal experimentation and follow the 3R principle (Replacement, Reduction, and Refinement).</p>

						<?php } else if($_GET["osio"] == "publications") { ?>

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>research/">Research</a><span class="sep">&raquo;</span><span>Publications</span></div>

							<h2> Publications </h2>

							<div id="publications">

								<div class="spinParticleContainer" data-name="home_spinner">
									<div class="particle red"></div>
									<div class="particle grey other-particle"></div>
									<div class="particle blue other-other-particle"></div>
								</div>

							</div>

							<?php /*<p>Lavonius M,&nbsp;Suvitie&nbsp;P, Varpe P, Huhtinen H. <a href="https://www.ncbi.nlm.nih.gov/pubmed/28367344">Sacral Neuromodulation: Foray into Chronic Pelvic Pain in End Stage&nbsp;Endometriosis.</a> Case Rep Neurol Med. 2017;2017:2197831. doi: 10.1155/2017/2197831.</p>

							<p>Ebert AD, Dong L, Merz M, Kirsch B, Francuski M, B&ouml;ttcher B, Roman H,&nbsp;Suvitie&nbsp;P, Hlavackova O, Gude K, Seitz C. <a href="https://www.ncbi.nlm.nih.gov/pubmed/28189702">Dienogest 2&nbsp;mg Daily in the Treatment of Adolescents with Clinically Suspected&nbsp;Endometriosis: The Visanne Study to Assess Safety in Adolescents.</a> J Pediatr Adolesc Gynecol. 2017 Feb 9. pii: S1083-3188(17)30036-0. doi: 10.1016/j.jpag.2017.01.014.</p>

							<p>Gidwani K, Huhtinen K, Kekki H, van Vliet S, Hynninen J, Koivuviita N, Perheentupa A,&nbsp;Poutanen&nbsp;M, Auranen A, Grenman S, Lamminm&auml;ki U, Carpen O, van Kooyk Y, Pettersson K. <a href="https://www.ncbi.nlm.nih.gov/pubmed/27540033">A Nanoparticle-Lectin Immunoassay Improves Discrimination of Serum CA125 from Malignant and Benign Sources.</a> Clin Chem. 2016 Oct;62(10):1390-400. doi: 10.1373/clinchem.2016.257691.</p>

							<p>Suvitie&nbsp;PA, Hallamaa MK, Matom&auml;ki JM, M&auml;kinen JI, Perheentupa AH. <a href="https://www.ncbi.nlm.nih.gov/pubmed/26169662">Prevalence of Pain Symptoms Suggestive of&nbsp;Endometriosis&nbsp;among Finnish Adolescent Girls (TEENMAPS Study).</a> J Pediatr Adolesc Gynecol. 2016 Apr;29(2):97-103. doi: 10.1016/j.jpag.2015.07.001.</p>

							<p>Huhtinen K, Saloniemi-Heinonen T, Keski-Rahkonen P, Desai R, Laajala D, St&aring;hle M, H&auml;kkinen MR, Awosanya M, Suvitie P, Kujari H, Aittokallio T, Handelsman DJ, Auriola S, Perheentupa A,&nbsp;Poutanen&nbsp;M. <a href="https://www.ncbi.nlm.nih.gov/pubmed/25137424">Intra-tissue steroid profiling indicates differential progesterone and testosterone metabolism in the endometrium and&nbsp;endometriosis&nbsp;lesions.</a> J Clin Endocrinol Metab. 2014 Nov;99(11):E2188-97. doi: 10.1210/jc.2014-1913.</p>

							<p>Vehmas AP, Muth-Pawlak D, Huhtinen K, Saloniemi-Heinonen T, Jaakkola K, Laajala TD, Kaprio H, Suvitie PA, Aittokallio T, Siitari H, Perheentupa A,&nbsp;Poutanen&nbsp;M, Corthals GL. <a href="https://www.ncbi.nlm.nih.gov/pubmed/25099244">Ovarian&nbsp;endometriosis&nbsp;signatures established through discovery and directed mass spectrometry analysis.</a> J Proteome Res. 2014 Nov 7;13(11):4983-94. doi: 10.1021/pr500384n.</p>

							<p>Keski-Rahkonen P, Huhtinen K, Desai R, Harwood DT, Handelsman DJ, Poutanen M, Auriola S. <a href="https://www.ncbi.nlm.nih.gov/pubmed/24078246">LC-MS analysis of estradiol in human serum and endometrial tissue: Comparison of electrospray ionization, atmospheric pressure chemical ionization and atmospheric pressure photoionization.</a> J Mass Spectrom. 2013 Sep;48(9):1050-8. doi: 10.1002/jms.3252.</p>

							<p>Huhtinen K, Desai R, St&aring;hle M, Salminen A, Handelsman DJ, Perheentupa A,&nbsp;Poutanen&nbsp;M. <a href="https://www.ncbi.nlm.nih.gov/pubmed/22969138">Endometrial and endometriotic concentrations of estrone and estradiol are determined by local metabolism rather than circulating levels.</a> J Clin Endocrinol Metab. 2012 Nov;97(11):4228-35. doi: 10.1210/jc.2012-1154.</p>

							<p>Hallamaa M, Suvitie P, Huhtinen K, Matom&auml;ki J,&nbsp;Poutanen&nbsp;M, Perheentupa A. <a href="https://www.ncbi.nlm.nih.gov/pubmed/22426487">Serum HE4 concentration is not dependent on menstrual cycle or hormonal treatment among&nbsp;endometriosis&nbsp;patients and healthy premenopausal women.</a> Gynecol Oncol. 2012 Jun;125(3):667-72. doi: 10.1016/j.ygyno.2012.03.011.</p>

							<p>Poutanen M. <a href="https://www.ncbi.nlm.nih.gov/pubmed/22128338">Understanding the diversity of sex steroid action.</a> J Endocrinol. 2012 Jan;212(1):1-2. doi: 10.1530/JOE-11-0414.</p>

							<p>Huhtinen K, Perheentupa A,&nbsp;Poutanen&nbsp;M, Heikinheimo O. <a href="https://www.ncbi.nlm.nih.gov/pubmed/21995119">[Pathogenesis of&nbsp;endometriosis].</a>&nbsp;Duodecim. 2011;127(17):1827-35. Finnish.</p>

							<p>Grimaldi G, Christian M, Steel JH, Henriet P,&nbsp;Poutanen<strong> </strong>M, Brosens JJ. <a href="https://www.ncbi.nlm.nih.gov/pubmed/21903722">Down-regulation of the histone methyltransferase EZH2 contributes to the epigenetic programming of decidualizing human endometrial stromal cells.</a> Mol Endocrinol. 2011 Nov;25(11):1892-903. doi: 10.1210/me.2011-1139.</p>

							<p>Huhtinen K, St&aring;hle M, Perheentupa A,&nbsp;Poutanen&nbsp;M. <a href="https://www.ncbi.nlm.nih.gov/pubmed/21875644">Estrogen biosynthesis and signaling in&nbsp;endometriosis.</a> Mol Cell Endocrinol. 2012 Jul 25;358(2):146-54. doi: 10.1016/j.mce.2011.08.022.</p>

							<p>Keski-Rahkonen P, Huhtinen K,&nbsp;Poutanen&nbsp;M, Auriola S. <a href="https://www.ncbi.nlm.nih.gov/pubmed/21684334">Fast and sensitive liquid chromatography-mass spectrometry assay for seven androgenic and progestagenic steroids in human serum.</a> J Steroid Biochem Mol Biol. 2011 Nov;127(3-5):396-404. doi: 10.1016/j.jsbmb.2011.06.006.</p>

							<p>Hiissa J, Elo LL, Huhtinen K, Perheentupa A,&nbsp;Poutanen&nbsp;M, Aittokallio T. <a href="https://www.ncbi.nlm.nih.gov/pubmed/19663710">Resampling reveals sample-level differential expression in clinical genome-wide studies.</a> OMICS. 2009 Oct;13(5):381-96. doi: 10.1089/omi.2009.0027.</p>

							<p>Huhtinen K, Suvitie P, Hiissa J, Junnila J, Huvila J, Kujari H, Set&auml;l&auml; M, H&auml;rkki P, Jalkanen J, Fraser J, M&auml;kinen J, Auranen A,&nbsp;Poutanen&nbsp;M, Perheentupa A. <a href="https://www.ncbi.nlm.nih.gov/pubmed/19337252">Serum HE4 concentration differentiates malignant ovarian tumours from ovarian endometriotic cysts.</a> Br J Cancer. 2009 Apr 21;100(8):1315-9. doi: 10.1038/sj.bjc.6605011.</p>
*/ ?>
						<?php } ?>

					</div>

				</article>

				<?php } else if($_GET["sivu"] == "collaboration") { ?>

					<article class="content flex">

						<div class="content_nav">

							<a href="<?php echo URL.$logged_url; ?>collaboration/"<?php echo (!isset($_GET["osio"])) ? ' class="selected"' : ''; ?>> Collaboration </a>
						</div>

						<div id="overview" class="content_article">

							<?php if(!isset($_GET["osio"])) { ?>

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><span>collaboration</span></div>

							<h2> Collaboration </h2>

							<h3 class="title">Hospitals</h3>

							<div class="image-wrapper">
							<?php
							$images = glob(ROOT."images/collaboration/Hospitals/"."*.{jpg,JPG,gif,png}",GLOB_BRACE);
							foreach($images as $image) {


								echo '<img src="'.URL."images/collaboration/Hospitals/".basename($image).'" alt="" '.((basename($image) == "HUS_logo.jpg") ? 'data-text="Hospital District of Helsinki and Uusimaa"' : '').' />';

							}
							?>
							</div>

							<h3 class="title">Academic Institutions</h3>

							<div class="image-wrapper">
							<?php
							$images = glob(ROOT."images/collaboration/Institutions/"."*.{jpg,gif,png}",GLOB_BRACE);
							foreach($images as $image) { echo '<img src="'.URL."images/collaboration/Institutions/".basename($image).'" '.((basename($image) == "FIMM_logo_final_rgb.jpg") ? 'data-text="Institute for Molecular Medicine Helsinki"' : '').' alt="" />'; }
							?>
							</div>

							<h3 class="title">Companies / Funding partners</h3>

							<div class="image-wrapper">
							<?php
							$images = glob(ROOT."images/collaboration/Companies/"."*.{jpg,gif,png}",GLOB_BRACE);
							foreach($images as $image) { echo '<img src="'.URL."images/collaboration/Companies/".basename($image).'" '.((basename($image) == "VTT_RGB_Large.png") ? 'data-text="Technical Research Centre of Finland"' : '').' alt="" />'; }
							?>
							</div>

							<h3 class="title">Patient organization</h3>

							<div class="image-wrapper">
								<img src="<?php echo URL; ?>images/collaboration/FinnishEndologo.png" alt="" />
							</div>

							<?php } ?>

						</div>

					</article>

				<?php } else if($_GET["sivu"] == "people") { ?>

						<article class="content flex">

							<div class="content_nav">

								<a href="<?php echo URL.$logged_url; ?>contact/" class="selected"> Endomet Team </a>

							</div>

							<div id="overview" class="content_article">

								<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><span>Endomet Team</span></div>

								<h2> People </h2>

								<h3 class="title">Preclinical Science</h3>

								<div class="people-wrapper">

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Poutanen_Matti-16.jpg)"></figure>
										<p class="small">Matti Poutanen PhD<br />
											Institute of Biomedicine, <br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Taija_Heinosalo.jpg)"></figure>
										<p>Taija Heinosalo PhD<br />
											Institute of Biomedicine,<br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Michael Gabriel MD<br />
											Department of Obstetrics and Gynecology<br />
											Institute of Biomedicine,<br />
											University of Turku, Turku<br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Kaisa Huhtinen PhD<br />
											Department of Pathology <br />
											Institute of Biomedicine, <br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Kalle Rytkönen PhD<br />
											Turku Bioscience Centre, <br />
											University of Turku and Åbo Akademi, Turku <br />
											Finland</p>
									</div>

								</div>

								<h3 class="title">Clinical Science</h3>

								<div class="people-wrapper">

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Antti_Perheentupa.png)"></figure>
										<p>Antti Perheentupa MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Pia_Suvitie.png"></figure>
										<p>Pia Suvitie MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Carita_Edgren.png)"></figure>
										<p> Carita Edgren MD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Marianne_Hallamaa.jpg)"></figure>
										<p>Marianne Hallamaa MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Harry_Kujari.jpg)"></figure>
										<p>Harry Kujari MD <br />
											Department of Pathology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Paivi_Rosneberg.jpg"></figure>
										<p>Päivi Rosenberg midwife<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Kirisi Joronen MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital district of Southwestern Finland, <br />
											Turku, Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Jyrki Jalkanen, MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Central Finland Health Care District <br />
											Jyvaskyla, Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Päivi Härkki, MD, PhD<br />
											Department of Obstetrics and Gynecology <br />
											Hospital District of Helsinki and Uusima (HUS) <br />
											Helsinki, Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Marjaleena Setälä, MD<br />
											Department of Obstetrics and Gynecology<br />
											Päijät-Häme Hospital<br />
											Lahti, Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Jaana Fraser, MD<br />
											North Karelia Central Hospital,
											Joensuu, Finland</p>
									</div>

								</div>

								<h3 class="title">Bioinformatics</h3>

								<div class="people-wrapper">

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Tero_Aittokallio.jpg)"></figure>
										<p>Tero Aittokallio PhD<br />
											Institute for Molecular Medicine Finland (FIMM), <br />
											University of Helsinki, Helsinki <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Harri Siitari PhD<br />
											Department of Neurology,
											University of Turku, Turku
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Arho Virkki PhD<br />
											Department of Mathematics and Statistics,<br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Vidal_Fey.jpg)"></figure>
										<p>Vidal Fey PhD<br />
											Institute of Biomedicine,<br />
											University of Turku, Turku<br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Prem Adhikari PhD<br />
											Institute of Biomedicine, <br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-image: url(<?php echo URL; ?>images/people/Daniel_Laajala.png)"></figure>
										<p>Daniel Laajala PhD<br />
											Department of Mathematics and Statistics,<br />
											University of Turku, Turku<br />
											Finland</p>
									</div>

									<div class="people">
										<figure style="background-color: #fff;"></figure>
										<p>Tuomo Komulainen<br />
											Institute of Biomedicine,<br />
											University of Turku, Turku <br />
											Finland</p>
									</div>

								</div>

							</div>

						</article>

				<?php } else if($_GET["sivu"] == "contact") { ?>

					<article class="content flex">

						<div class="content_nav">

							<a href="<?php echo URL.$logged_url; ?>contact/" class="selected"> Contact </a>

						</div>

						<div id="overview" class="content_article">

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><span>Contact</span></div>

							<div class="contact-container">
								<figure style="background-image: url(<?php echo URL; ?>images/contact_1.jpg)"></figure>
								<div class="contact-info">
									<h2>Prof. Matti Poutanen, Ph.D.</h2>
									<h3>Director, Turku Center for Disease Modeling</h3>
									<p>Professor of Physiology,<br />
									Department of Physiology,<br />
									Institute of Biomedicine,<br />
									Kiinamyllynkatu 10,<br />
									20014 University of Turku</p>
									<p><a href="mailto:matti.poutanen@utu.fi">matti.poutanen@utu.fi</a></p>
									<p><a href="http://www.tcdm.fi/" target="_blank">www.tcdm.fi</a></p>
								</div>
							</div>

							<div class="contact-container">

								<figure style="background-image: url(<?php echo URL; ?>images/people/Antti_Perheentupa.png)"></figure>
								<div class="contact-info">
									<h2>Antti Perheentupa, M.D. Ph.D.</h2>
									<h3>Physician in charge outpatient clinic and infertility treatment</h3>
									<p>Adjunct Prof.<br />
									Department of Obstetrics and Gynecology,<br />
									Hospital district of Southwestern Finland,<br />
									Kiinamyllynkatu 4-8,<br />
									PL 52, 20521 Turku Finland</p>
									<a href="mailto:antti.perheentupa@tyks.fi">antti.perheentupa@tyks.fi</a></p>
								</div>
								​
							</div>

						</div>

					</article>

				<?php } else if($_GET["sivu"] == "tools") { ?>

					<article class="content flex">

						<div class="content_nav">
							<a href="<?php echo URL.$logged_url; ?>tools/" class="selected"> Tools </a>
							<a href="<?php echo URL.$logged_url; ?>gene_analysis/"> Gene analysis </a>
							<a href="<?php echo URL.$logged_url; ?>endometriosis_risk_assessment/"> Endometriosis Risk Assessment </a>
						</div>

						<div id="overview" class="content_article">

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><span>Tools</span></div>

							<h2> Tools </h2>

							<a href="<?php echo URL.$logged_url; ?>gene_analysis/"><button class="btn large green"> Gene analysis </button></a>
							<a href="<?php echo URL.$logged_url; ?>endometriosis_risk_assessment/"><button class="btn large green"> Endometriosis Risk Assessment </button></a>


						</div>

					</article>

				<?php } else if($_GET["sivu"] == "analysis") { // Uutta 12.06.2020 ?>

					<?php if(!isset($_GET["osio"])) { ?>

					<article class="content">

						<div class="links" data-row="3">

							<div class="link">
								<a href="<?php echo URL.$logged_url; ?>analysis/database"><img src="<?php echo URL; ?>images/nostot/analysis_2.jpg" style="border-bottom: 1px solid #ddd;" alt="" /></a>
								<h3> Endomet database </h3>
								<p> EndometDB is repository of microarray gene expression profile data for normal endometrium, peritoneum and endometriosis lesions. </p>
							</div>

							<div class="link">
								<a href="<?php echo URL.$logged_url; ?>analysis/statistics/"><img src="<?php echo URL; ?>images/nostot/analysis_1.jpg" alt="" /></a>
								<h3> Tissue & Sample statistics </h3>
								<p> Dataset summary of analysis samples. </p>
							</div>

							<div class="link">
								<a href="<?php echo URL.$logged_url; ?>analysis/guide"><img src="<?php echo URL; ?>images/nostot/analysis_3.jpg" alt="" /></a>
								<h3> Guide </h3>
								<p> A guide for how to use the gene analysis tool. </p>
							</div>

						</div>

					</article>

					<?php } else { ?>

						<article class="content flex">

							<div class="content_nav">

								<a href="<?php echo URL.$logged_url; ?>analysis/database/"<?php echo ($_GET["osio"] == "database") ? ' class="selected"' : ''; ?>> Endomet database  </a>
								<a href="<?php echo URL.$logged_url; ?>analysis/statistics/"<?php echo ($_GET["osio"] == "statistics") ? ' class="selected"' : ''; ?>> Tissue & Sample statistics  </a>
								<a href="<?php echo URL.$logged_url; ?>analysis/guide/"<?php echo ($_GET["osio"] == "guide") ? ' class="selected"' : ''; ?>> Guide </a>

							</div>

							<?php if($_GET["osio"] == "database") { ?>

								<div id="overview" class="content_article">

									<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>analysis/">Gene analysis</a><span class="sep">&raquo;</span><span>Database</span></div>
									<h2> Database </h2>
									<p>Lorem ipsum </p>

								</div>

							<?php } else if($_GET["osio"] == "statistics") { ?>

								<div id="overview" class="content_article" id="demo-home" style="position: relative; min-height: 570px;">

									<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>analysis/">Gene analysis</a><span class="sep">&raquo;</span><span>Tissue & Sample statistics</span></div>
									<h2> Tissue & Sample statistics  </h2>

									<?php /*<button class="btn green" data-name="start_analysis"> Start Gene Expression Analysis </button> */ ?>

									<div class="spinParticleContainer" data-name="home_spinner">
										<div class="particle red"></div>
										<div class="particle grey other-particle"></div>
										<div class="particle blue other-other-particle"></div>
									</div>

									<div id="test_svg"></div>

									<div id="statistics" style="display: none;"> <?php /* style="display: none;" || class="plotly_grid" */ ?>

							            <div class="block_area grid" data-grid="3">

							                <div class="block">

							                    <div class="block_inner">

							                        <h1>Patients / controls</h1>

							                        <div class="donut-chart" data-name="patient">
							                            <div class="info hide"></div>
							                            <div class="donuts"></div>
							                        </div>

							                        <div class="bullets_wrapper">
							                            <ul class="bullets"></ul>
							                        </div>

							                        <div class="clear"></div><p class="havainnot_total" style="margin: 20px 0 0 0;">Total Patients: <b>2247</b>, Total Samples: <b>19847</b></p>

							                    </div>

							                </div>

							                <!-- -->

							                <div class="block">

							                    <div class="block_inner">

							                        <h1>Samples by tissue type</h1>

							                        <div class="donut-chart" data-name="tissue">
														<div class="info hide"></div>
							                            <div class="donuts"></div>
							                        </div>

							                        <div class="bullets_wrapper">
							                        	<ul class="bullets"></ul>
							                        </div>

							                    </div>

							                </div> <!-- Block -->

							            </div> <!-- Block area -->

									</div> <!-- statistics -->

								</div> <!-- Article -->

								<?php /*<script src="<?php echo URL; ?>js/plotly-latest.min.js"></script> */ ?>

							<?php } else if($_GET["osio"] == "guide") { ?>

								<div id="lightbox"></div>

								<div id="overview" class="content_article">

									<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Home</a><span class="sep">&raquo;</span><a href="<?php echo URL.$logged_url; ?>analysis/">Gene analysis</a><span class="sep">&raquo;</span><span>How to guide</span></div>

									<h2> Getting Started </h2>

									<p><strong>User guide in PDF-format</strong></p>

									<a href="<?php echo URL; ?>images/EndometDB_Guide.pdf" target="_blank">EndometDB_Guide.pdf</a>

									<p><strong>User Interface </strong></p>

									<p>EndometDB supports all internet enabled devices using a modern browser. It utilizes HTML5, JavaScript, PHP, and R as the main programming languages. The interactive visualization is implemented using the Plotly open source JavaScript graphing library.</p>

									<p>When the EndometDB opens, the interface features a window divided into panes: the filter control in the left pane, and a tabbed browser in the right pane.</p>

									<a class="fnbx" href="<?php echo URL; ?>images/guide/1.png" target="_blank"><img src="<?php echo URL; ?>images/guide/1.png" class="full" alt="EndometDB" /></a>

									<p><strong>Filter Control</strong></p>

									<p> The left pane of the main window displays a filter control that allows users interact with the data in the EndometDB. </p>

									<p> the nodes in the filter control can be expanded to access context menus by clicking in the check box. The filter control expands to display options that includes one or more of the following selections: </p>

									<a class="fnbx" href="<?php echo URL; ?>images/guide/2.png" target="_blank"><img src="<?php echo URL; ?>images/guide/2.png" style="width: auto;" class="full" alt="EndometDB" /></a>

									<p> the nodes in the filter control can be expanded to access context menus by clicking in the check box. The filter control expands to display options that includes one or more of the following selections: </p>

									<table class="guide">
										<tbody>
											<tr>
												<th>
													<p><strong>Option</strong></p>
												</th>
												<th>
													<p><strong>Action</strong></p>
												</th>
											</tr>
											<tr>
												<td>
												<p><strong>Clinical data</strong></p>
												</td>
												<td>
												<p>Click in check box to display clinical data menus that includes data such as age, cycle phase &amp; hormonal medication use, and disease stage.</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Age</strong></p>
												</td>
												<td>
												<p>Click in check box to display age categories</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Cycle phase &amp; Hormonal medication</strong></p>
												</td>
												<td>
												<p>Click in check box to display cycle phase and hormonal medication use</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Disease Stage</strong></p>
												</td>
												<td>
												<p>Click in check box to display the different stages of disease</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Sample data</strong></p>
												</td>
												<td>
												<p>Click in check box to display the subject class menus</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Subject class</strong></p>
												</td>
												<td>
												<p>Click in check box to display the sample type menu</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Sample type</strong></p>
												</td>
												<td>
												<p>Selected by defaults displays the tissues and the lesions</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Tissue</strong></p>
												</td>
												<td>
												<p>Selected by default to display the different tissue types plus the lesion</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Lesions</strong></p>
												</td>
												<td>
												<p>Selected by default under tissue menu displays the different lesion types</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Modules</strong></p>
												</td>
												<td>
												<p>Click in check box to display the gene symbols menu with text box to enter gene interest or copy paste genes of interest.</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Gene symbols</strong></p>
												</td>
												<td>
												<p>Entering gene of interest or copy pasting genes displays Statistics &amp; Projections menu</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Clear</strong></p>
												</td>
												<td>
												<p>Clear button clears all the genes in the text box</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Statistics and Projections</strong></p>
												</td>
												<td>
												<p>Select plots from drop down menu to display different functions available</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Run analysis</strong></p>
												</td>
												<td>
												<p>Run analysis button executes the</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Reset</strong></p>
												</td>
												<td>
												<p>Resets all to default settings</p>
												</td>
											</tr>
											<tr>
												<td>
												<p><strong>Close</strong></p>
												</td>
												<td>
												<p>Closes current browser except for the default browser</p>
												</td>
											</tr>
										</tbody>
									</table>

									<p><strong>Filter Control</strong></p>

									<p> The right pane of the EndometDB window feature a tab browser that displays plots and projections from gene or genes of interest with different selected options in the filter control in the left window. The plus sign (+) to the right of the browser appears when an initial plot is done. Use the plus sign (+) to add more tabs. </p>

									<a class="fnbx" href="<?php echo URL; ?>images/guide/3.png" target="_blank"><img src="<?php echo URL; ?>images/guide/3.png" style="width: auto;" class="full" alt="EndometDB" /></a>

								</div>

							<?php } ?>

						</article>

					<?php } ?>

				<?php } else if($_GET["sivu"] == "gene_analysis") { ?>

					<article class="content" id="demo-content">

						<div class="content_article demo">

							<div class="breadcrumbs"><a href="<?php echo URL; ?>">Home</a><span class="sep">&raquo;</span><span>Gene Analysis</span></div>

							<div class="analysis_tabs">
								<div class="filter_tabs">
									<div class="tab minify"><i class="fa fa-angle-double-left"></i><span>Hide filters</span></div>
								</div>
								<div class="filter_tabs">
									<div class="tab analysis selected" data-index="0"><i class="fa fa-bar-chart"></i><span>Analysis</span></div>
									<div class="tab new_tab hide"><i class="fa fa-plus"></i></div>
								</div>
							</div>
							<div id="wrapper" class="min_height">
								<?php require_once(VIEWS."analysis_home.php"); ?>
							</div>
						</div>

					</article>

					<script src="<?php echo URL; ?>js/plotly-latest.min.js"></script>

				<?php } else if($_GET["sivu"] == "endometriosis_risk_assessment") { ?>

					<article class="content flex">

						<div class="content_nav">

							<a href="<?php echo URL.$logged_url; ?>tools/"> Tools </a>
							<a href="<?php echo URL.$logged_url; ?>gene_analysis/"> Gene analysis </a>
							<a href="<?php echo URL.$logged_url; ?>endometriosis_risk_assessment/" class="selected"> Endometriosis Risk Assessment </a>

						</div>

						<div id="overview" class="content_article">

							<div class="breadcrumbs"><a href="<?php echo URL.$logged_url; ?>">Tools</a><span class="sep">&raquo;</span><span>Endometriosis Risk Assessment</span></div>

							<h2> Endometriosis Risk Assessment </h2>

							<p>Early diagnosis of endometriosis is challenging and as a result, there is a 7-10-year delay in diagnosis from the beginning of the symptoms to final diagnosis. We developed an Endometriosis Risk Assessment tool, using machine learning for individual endometriosis risk prediction. The tool was developed using large questionnaire data set collected from healthy women and endometriosis patients<strong>.</strong></p>

							<p>Here you can anonymously answer a series of questions related to you symptoms and obtain a personal risk of having endometriosis. With increased risk as a result, it is advisable to contact a gynecologist.</p>

							<a href="#" target="_blank"><button class="btn green"> Click here to start </button></a>

						</div>

					</article>

				<?php } ?>

		<footer>

			<div class="section">
				<h2> Visiting address</h2>
				<p> Kiinamyllynkatu 4-8<br />
					20521 Turku</p>
			</div>

			<div class="section">
				<h2>Postal address</h2>
				<p> Women’s Clinic <br />
				PO Box 52<br />
				   20521 Turku</p>
			</div>

			<div class="section">
				<h2>Contact info</h2>

				<p>naistentautien.poliklinikka@tyks.fi<br />
				tel: 02 313 0000 Switchboard<br />
				fax: 02 313 2323
				</p>
			</div>

			<div class="clear"></div>
			<div class="bottom">

				<span>&copy; Turku Endometriosis Database <?php echo date("Y"); ?></span>
				<?php /*<a href="#"><img src="<?php echo URL; ?>images/facebook.png" alt="Facebook" /></a>
				<a href="#"><img src="<?php echo URL; ?>images/twitter.png" alt="Twitter" /></a>
				<a href="#"><img src="<?php echo URL; ?>images/linkedin.png" alt="Linkedin" /></a>*/ ?>

			</div>

		</footer>


	</div>

</div>

<?php require_once("home_footer.php"); ?>
