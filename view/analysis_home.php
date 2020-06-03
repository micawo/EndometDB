<?php

require_once(MGMT."analysis.php");
$analysis = new EndometDBAnalysis();

?>
<section id="filters">

    <div class="filters_inner">

        <div class="chart_btns">
			<button class="btn black" data-name="reset"> Reset </button>
			<button class="btn black" data-name="close" style="display: none;"> Close </button>
		</div>


    <?php  echo $analysis->getFilters("home"); ?>

    <div id="chart_settings" class="filter hidden">

        <header class="filter-header">
            <div class="filter-toggle"></div>
            <h3 class="filter-title">Statistics & Projections</h3>
        </header>

        <div class="content">

            <!-- Chart type select -->
            Select plots
            <div class="select" data-id="filter-type" data-type="box" style="margin-left: 5px; margin-bottom: 10px;">

                <span><i class="icon-plot-box"></i>Box plot</span>

                <div class="select-content row-content">

                    <div class="row">
                      <h3>Statistics</h3>

                       <div class="filter-select" data-type="box">
                         <i class="icon-plot-box"></i>
                         Box plot
                       </div>

                       <div class="filter-select" data-type="heatmap">
                         <i class="icon-plot-heatmap"></i>
                         Heatmap
                       </div>

                       <div class="filter-select" data-type="correlation">
                         <i class="icon-plot-bar"></i>
                         Correlation
                       </div>
                    </div>

                    <div class="row">
                        <h3>Projections</h3>

                        <div class="filter-select" data-type="pca">
                          <i class="icon-plot-hist"></i>
                          Principal Component Analysis (PCA)
                                  </div>

                         <div class="filter-select" data-type="mds">
                           <i class="icon-contour"></i>
                           Multidimensional Scaling (MDS)
                         </div>

                        <div class="filter-select" data-type="lfda">
                           <i class="icon-contour"></i>
                           Local Fisher Discriminant Analysis (LFDA)
                        </div>
                    </div>

                </div>

            </div>

				<!-- Chart settings -->

            <div class="chart_settings_filter" data-type="box|heatmap">

                <div class="fl checkbox checked" data-name="combine_lesions">
                    <figure><i class="fa fa-check"></i></figure>Combine lesions
                </div>

            </div>

            <div class="chart_settings_filter" data-type="box|heatmap|pca|mds|lfda">

					<div class="fl checkbox checked" data-name="log2">
						<figure><i class="fa fa-check"></i></figure>Use log2-scale
                </div>

            </div>

				<div class="chart_settings_filter" data-type="box|heatmap|pca|mds|lfda">

                <div class="fl checkbox checked" data-name="legend">
                    <figure><i class="fa fa-check"></i></figure>Show legend
                </div>

            </div>

				<div class="chart_settings_filter hidden" data-type="pca">

	                <div class="fl checkbox" data-name="plot_scree">
	                    <figure><i class="fa fa-check"></i></figure>Show scree plot
	                </div>

        </div>

            <div class="chart_settings_filter hidden" data-type="lfda">

                <label>Metric</label>

                <div class="fl select full" data-value="plain" data-name="lfda_metric">
                    <span>Raw eigenvectors</span>
                    <div class="select-content no-padding">
                        <div class="option" data-value="plain">Raw eigenvectors</div>
                        <div class="option" data-value="weighted">Weighted eigenvectors</div>
                        <div class="option" data-value="orthonormalized">Orthonormalized</div>
                    </div>
                </div>

            </div>
            <div class="chart_settings_filter" data-type="box">

                <div class="fl checkbox" data-name="plot_counts">
                    <figure><i class="fa fa-check"></i></figure>Display sample counts
                </div>

            </div>

            <div class="chart_settings_filter hidden" data-type="heatmap">

                <label> Summarise by </label>

                <div class="fl select full" data-value="median" data-name="summarise_by">
                    <span>Median</span>
                    <div class="select-content no-padding">
                        <div class="option" data-value="median">Median</div>
                        <div class="option" data-value="mean">Mean</div>
                    </div>
                </div>

            </div>

            <div class="chart_settings_filter hidden" data-type="heatmap">

                <label> Data Centring </label>

                <div class="fl select full" data-value="Gene" data-name="data_centering">
                    <span>Gene</span>
                    <div class="select-content no-padding">
                        <div class="option" data-value="Gene">Gene</div>
                        <div class="option" data-value="Lesions">Lesions</div>
                        <div class="option" data-value="none">none</div>
                    </div>
                </div>

            </div>

        <div class="chart_settings_filter hidden" data-type="heatmap">
            <div class="fl checkbox" data-name="cluster_heatmap">
                <figure><i class="fa fa-check"></i></figure>Cluster heatmap
            </div>
        </div>

			<div class="chart_settings_filter hidden" data-type="heatmap|mds">

				<div class="chart_settings_filter hidden" data-type="heatmap">
            <div class="fl checkbox checked" data-name="expand-legend-acronyms">
                  <figure><i class="fa fa-check"></i></figure>Expand legend acronyms
            </div>
        </div>

				<label> Distance Metric </label>

				<div class="fl select full" data-value="euclidean" data-name="distance_metric">
					<span>Euclidean</span>
					<div class="select-content no-padding">
						<div class="option" data-value="euclidean">Euclidean</div>
						<div class="option" data-value="canberra">Canberra</div>
						<div class="option" data-value="manhattan">Manhattan</div>
						<div class="option" data-value="maximum">Maximum</div>
						<div class="option" data-value="minkowski">Minkowski</div>
					</div>
				</div>

			</div>

			<div class="chart_settings_filter hidden" data-type="correlation">
				<label> Correlation method </label>
				<div class="fl select full" data-value="pearson" data-name="correlation_method">
					<span>Pearson</span>
					<div class="select-content no-padding">
						<div class="option" data-value="pearson">Pearson</div>
						<div class="option" data-value="spearman">Spearman</div>
						<div class="option" data-value="kendall">Kendall</div>
					</div>
				</div>
			</div>

			<div class="chart_settings_filter hidden" data-type="heatmap|correlation">
				<label> Clustering method </label>
				<div class="fl select full" data-value="complete" data-name="clustering_method">
					<span>Complete linkage</span>
					<div class="select-content no-padding">
						<div class="option" data-value="complete">Complete linkage</div>
						<div class="option" data-value="single">Single linkage</div>
						<div class="option" data-value="average">Average linkage</div>
						<div class="option" data-value="ward.D2">Ward's method</div>
					</div>
				</div>
			</div>

            <!--<div class="chart_settings_filter hidden" data-type="heatmap">
                <div class="fl checkbox" data-name="scale">
                    <figure><i class="fa fa-check"></i></figure>Scale by Median Absolute Deviation [MAD] or Standard Deviation [SD]
                </div>
            </div>-->

			<div class="chart_settings_filter hidden" data-type="pca|mds|lfda">

				<label> Colour by </label>

				<div class="fl select full" data-value="Tissues" data-name="color_by">
					<span>Tissues</span>
					<div class="select-content no-padding">
						<div class="option" data-value="Tissues">Tissues</div>
						<div class="option" data-value="Disease State">Subject class</div>
						<div class="option" data-value="Disease Stage">Disease stage</div>
						<div class="option" data-value="Cycle Phase">Cycle phase</div>
						<div class="option" data-value="Age">Age</div>
						<!--<div class="option" data-value="Hormonal Medication Status">Hormonal Medication Status</div>-->
					</div>
				</div>

			</div>

			<div class="chart_settings_filter hidden" data-type="pca">
          <div class="fl checkbox" data-name="show_ellipses">
              <figure><i class="fa fa-check"></i></figure>Show 95% confidence ellipses
          </div>
      </div>
			<div class="chart_settings_filter hidden" data-type="">
          <div class="fl checkbox checked" data-name="label_ellipses">
              <figure><i class="fa fa-check"></i></figure>Label 95% confidence ellipses
          </div>
      </div>

			<div class="chart_settings_filter hidden" data-type="heatmap|correlation">

				<label> Color spectrum </label>

				<div class="fl gradients" data-name="spectrum">
          <figure data-value="RbG"></figure>
					<figure class="selected" data-value="RwB"></figure>
					<figure data-value="RdBu"></figure>
					<figure data-value="Spectral"></figure>
					<figure data-value="OrRd"></figure>
				</div>

			</div>

            <!-- / Chart type select -->

            <div class="filter-options"></div>

        </div>

    </div>

    <button class="btn black icon left fa-area-chart disabled" data-name="run_analysis"> Run analysis </button>

</div>

</section>

<section id="chart" class="run_again">

    <div class="chart-area"></div>

    <div class="spinParticleContainer">
        <div class="particle red"></div>
        <div class="particle grey other-particle"></div>
        <div class="particle blue other-other-particle"></div>
    </div>

    <div class="stillSpinParticleContainer">
        <div class="particle red"></div>
        <div class="particle grey"></div>
        <div class="particle blue"></div>
    </div>

</section>

<section id="float_nav" class="always_visible">

    <button class="btn black icon left fa-area-chart disabled" data-name="run_analysis_nav"> Run analysis </button>
    <button class="btn black icon left fa-download disabled" data-name="download_as_pdf_nav"> Download as PDF </button>
    <div id="scroll_to_top"> Scroll to top </div>

</section>
