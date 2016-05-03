@extends('customer.layouts.master')
@section('addHead')
  <title>Welcome | GameXtreme</title>
@endsection

@section('content')
<div class="wrap">
	<div class="header">
		@include('customer.includes.headerTop2')
		@include('customer.includes.headerTop')
		@include('customer.includes.mainMenu')
	</div>
	<div class="main">
		<div class="content">
			<div class="section group">
				<div class="col span_2_of_3">
					<div class="contact-form">
						<h2>
							Terms and Conditions of Use
						</h2>

						<h3>
							1. Terms
						</h3>
						<br>
						<p>
							By accessing this web site, you are agreeing to be bound by these 
							web site Terms and Conditions of Use, all applicable laws and regulations, 
							and agree that you are responsible for compliance with any applicable local 
							laws. If you do not agree with any of these terms, you are prohibited from 
							using or accessing this site. The materials contained in this web site are 
							protected by applicable copyright and trade mark law.
						</p>
						<br>
						<h3>
							2. Use of Products
						</h3>
						<br>
						<ol type="a">
							<li>
								Permission is granted to users who purchase the materials 
								(information or software) on GameXtreme's web site for personal, 
								non-commercial transitory use only. This is the grant of a license, 
								not a transfer of title, and under this license you may not:
								
								<ol type="i">
									<li>modify or copy the materials;</li>
									<li>use the materials for any commercial purpose, or for any public display (commercial or non-commercial);</li>
									<li>attempt to decompile or reverse engineer any software contained on GameXtreme's web site;</li>
									<li>remove any copyright or other proprietary notations from the materials; or</li>
									<li>transfer the materials to another person or "mirror" the materials on any other server.</li>
								</ol>
							</li>
							<li>
								This product's limited warranty will terminate if you violate any of these restrictions and may be terminated by GameXtreme at any time. Upon terminating your warranty of these materials or upon the termination of this warranty, all repairs and exchange costs should be shouldered by the consumer.
							</li>
						</ol>
						<br>
						<h3>
							3. Disclaimer
						</h3>
						<br>
						<ol type="a">
							<li>
								The materials on GameXtreme's web site are provided "as is". GameXtreme makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights. Further, GameXtreme does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its Internet web site or otherwise relating to such materials or on any sites linked to this site.
							</li>
						</ol>
						<br>
						<h3>
							4. Limitations
						</h3>
						<br>
						<p>
							In no event shall GameXtreme or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption,) arising out of the use or inability to use the materials on GameXtreme's Internet site, even if GameXtreme or a GameXtreme authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.
						</p>
						<br>		
						<h3>
							5. Revisions and Errata
						</h3>
						<br>
						<p>
							The materials appearing on GameXtreme's web site could include technical, typographical, or photographic errors. GameXtreme does not warrant that any of the materials on its web site are accurate, complete, or current. GameXtreme may make changes to the materials contained on its web site at any time without notice. GameXtreme does not, however, make any commitment to update the materials.
						</p>
						<br>
						<h3>
							6. Links
						</h3>
						<br>
						<p>
							GameXtreme has not reviewed all of the sites linked to its Internet web site and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by GameXtreme of the site. Use of any such linked web site is at the user's own risk.
						</p>
						<br>
						<h3>
							7. Site Terms of Use Modifications
						</h3>
						<br>
						<p>
							GameXtreme may revise these terms of use for its web site at any time without notice. By using this web site you are agreeing to be bound by the then current version of these Terms and Conditions of Use.
						</p>
						<br>
						<h3>
							8. Governing Law
						</h3>
						<br>
						<p>
							Any claim relating to GameXtreme's web site shall be governed by the laws of Alabang, Muntinlupa City without regard to its conflict of law provisions.
						</p>
						<br>
						<p>
							General Terms and Conditions applicable to Use of a Web Site.
						</p>
						<br>


						<h2>
							Privacy Policy
						</h2>
						<br>
						<p>
							Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.
						</p>
						<br>
						<ul>
							<li>
								Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.
							</li>
							<li>
								We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.		
							</li>
							<li>
								We will only retain personal information as long as necessary for the fulfillment of those purposes. 
							</li>
							<li>
								We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned. 
							</li>
							<li>
								Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date. 
							</li>
							<li>
								We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.
							</li>
							<li>
								We will make readily available to customers information about our policies and practices relating to the management of personal information. 
							</li>
						</ul>
						<br>
						<p>
							We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained. 
						</p>		
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('customer.includes.footer')
@endsection