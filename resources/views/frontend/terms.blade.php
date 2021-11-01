@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
		<div class="registration-bg form-group">
			<div class="col-md-8 col-md-push-2">
				<h1 class="widget-title text-center">{{trans('terms.Terms and Conditions')}}</h1>
				<p class="description text-center">{{trans('terms.Read Our Terms and Conditions')}}</p>
			</div>
		</div>
		<div>
			<div class="col-md-8 col-md-push-2">
				<div class="gap gap-small gap-border"></div>
				<h1>{{trans('terms.Conditions of Use')}}</h1>
				<p><b>{{trans('terms.Last updated: July 8th, 2018')}}</b></p>
				<div class="gap gap-small gap-border"></div>
				<h3>{{trans('terms.Use of the Goecolo.com website')}}</h3>
				<p>{{trans('terms.Welcome to Goecolo.com website, by accessing and using this Site, you unconditionally agree to be bound by these Terms of Use (the "Terms") and all applicable laws.')}}</p>

				<p><b>{{trans('terms.By using Goecolo.com Services, you agree to these conditions. Please read them carefully.')}}</b></p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Electronic Communications')}}</h3>
				<p>{{trans('terms.When you use any Goecolo.com Service, or send e-mails, text messages, and other communications from any device to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you electronically in a variety of ways, such as by e-mail, text, in-app push notices when available, or by posting notices and messages on the Goecolo.com site or through other Goecolo Services, such as our Dashboard Centre. You agree that all agreements, notices, messages, disclosures, and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Copyright')}}</h3>
				<p>{{trans('terms.All content included in or made available through any Goecolo.com Service--such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, and software--is the property of Goecolo.com or its content suppliers, and is protected by Canadian and international copyright laws. The compilation of all content included in or made available through any Goecolo.com Service is the exclusive property of Goecolo.com and protected by Canadian and international copyright laws.')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Trademarks')}}</h3>
				<p>{{trans("terms.In addition, graphics, logos, page headers, button icons, scripts, and service names included in or made available through any Goecolo.com Service are trademarks or trade dress of Goecolo.com Inc., or content providers in Canada and the United States and other countries. Goecolo.com, Inc.'s trademarks and trade dress may not be used in connection with any product or service that is not Goecolo Inc.'s, in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits Goecolo Inc. All other trademarks not owned by Goecolo Inc. that appear in any Goecolo.com Service are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by Goecolo Inc.")}} </p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.License and Access')}}</h3>
				<p>{{trans("terms.Subject to your compliance with these Conditions of Use and your payment of any applicable fees, Goecolo.com or its content providers grant you a limited, non-exclusive, non-transferable, non-sub licensable license to access and make personal and non-commercial use of the Goecolo.com Services. This license does not include any resale or commercial use of any Goecolo.com Service or its contents; any collection and use of any product listings, descriptions, or prices; any derivative use of any Goecolo.com Service or its contents; any downloading or copying of account information for the benefit of another merchant; or any use of data mining, robots, or similar data-gathering and extraction tools. All rights not expressly granted to you in these Conditions of Use or any Service Terms are reserved and retained by Goecolo.com or its licensors, suppliers, publishers, rights holders, or other content providers. No Goecolo.com Service, nor any part of any Goecolo.com Service, may be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without the express written consent of Goecolo.com. You may not frame or utilize framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of Goecolo.com without express written consent. You may not use any Meta tags or any other 'hidden text' utilizing Goecolo.com's name or trademarks without the express written consent of Goecolo.com. You may not misuse the Goecolo.com Services. You may use the Goecolo.com Services only as permitted by law. The licenses granted by Goecolo.com terminate if you do not comply with these Conditions of Use or any Service Terms.")}} </p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Your Account')}}</h3>
				<p>{{trans('terms.To place an order on our Website you need to have or create a Goecolo.com account and log in. You must make sure that your information is up-to-date at all times.')}} </p>

				<div class="gap gap-small"></div>
				<p>{{trans('terms.If you use any Goecolo.com Service, you are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer, and you agree to accept responsibility for all activities that occur under your account or password.')}}</p>

				<p>{{trans('terms.You alone are responsible for protecting the confidentiality of your user ID and password as well as the confidentiality of any actions undertaken using your identification. Moreover, you are responsible for notifying Goecolo.com immediately of any unauthorized use of your user name and password and any other breach of security, as well as for taking every possible precaution to ensure that you use the Site under optimal security conditions')}}</p>

				<p>{{trans('terms.The Goecolo.com site is reserved for persons who are of full legal age in their province of residence, and you must be of full legal age in your province of residence to place an order on our site.')}}</p>

				<p>{{trans('terms.If the order includes one or more age-restricted products (such as, but not limited to, alcohol) proof of full legal age in his or her province of residence is needed at delivery.')}}</p>

				<p>{{trans("terms.Goecolo abides by legal restrictions with respect to advertising to children. Parents are invited to monitor their children's Internet usage and are responsible, in their capacity as legal guardian, for determining which websites may or may not be appropriate for their children.")}}</p>

				<p>{{trans("terms.If you are under the age of majority in your province of residence, you may use the Goecolo.com Services only with involvement of a parent or guardian. Goecolo.com reserves the right to refuse service, terminate accounts, remove or edit content, or cancel orders in its sole discretion.")}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Reviews, Comments, Communications, and Other Content')}}</h3>
				<p>{{trans('terms.Visitors may post reviews, comments, photos, and other content; send e-cards and other communications; and submit suggestions, ideas, comments, questions, or other information, so long as the content is not illegal, obscene, threatening, defamatory, invasive of privacy, infringing of intellectual property rights, or otherwise injurious to third parties or objectionable, and does not consist of or contain software viruses, political campaigning, commercial solicitation, chain letters, mass mailings, or any form of "spam" or unsolicited commercial electronic messages You may not use a false e-mail address, impersonate any person or entity, or otherwise mislead as to the origin of a card or other content. Goecolo.com reserves the right (but not the obligation) to remove or edit such content, but does not regularly review posted content.')}}</p>
				<p>{{trans('terms.If you do post content or submit material, and unless we indicate otherwise, you grant Goecolo.com a nonexclusive, royalty-free, perpetual, irrevocable, and fully sub licensable right to use, produce, reproduce, modify, adapt, publish, perform, translate, create derivative works from, distribute, communicate to the public by telecommunications and display such content throughout the world in any media. You grant Goecolo.com and sub licensees the right to use the name that you submit in connection with such content, if they choose. You represent and warrant that you own or otherwise control all the rights to the content that you post; that the content is accurate; that use of the content you supply does not violate this policy and will not cause injury to any person or entity; and that you will indemnify Goecolo.com for all claims resulting from content you supply. Goecolo.com has the right but not the obligation to monitor and edit or remove any activity or content. Goecolo.com takes no responsibility and assumes no liability for any content posted by you or any third party.')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Order history')}}</h3>
				<p>{{trans('terms.The Site allows you to view all your online orders history.')}} </p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Risks associated with the Internet')}}</h3>
				<p>{{trans('terms.Errors, interruptions and viruses: The Site is accessible based on its availability. While Goecolo.com makes reasonable efforts to maintain the Site free of errors, interruptions and viruses, Goecolo.com makes no warranties, either express or implied, that the Site will operate without interruption, virus or error or in a secure and timely manner. You will be solely responsible for any damage to your computer or loss of data that may result when downloading material.')}} </p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Copyright Complaints')}}</h3>
				<p>{{trans('terms.Goecolo.com respects the intellectual property of others. If you believe that your work has been copied in a way that constitutes copyright infringement, please contact us on info@goecolo.com.')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Cancelation, Returns, Modifications, Refunds and Title')}}</h3>
				<p>{{trans('terms.You may cancel your order with full refund within one hour of paying for your order and before delivery, you will receive a full refund on the same mean of payment you will use, the refund payment is processed and subject to your service provider terms on refund (e.g. Banks and Other institutions).')}}
				</p>
				<p>{{trans('terms.You may return products at the time of delivery of your order. All product returns on delivery shall be processed for refund upon the return of the delivery person to the Designated Store within each store’s return policy. After delivery, you may, depending on the Designated Store’s refund and exchange policy, exchange or return any product in person at the Designated Store. Exchanges or returns by mail or other means of delivery shall not be accepted.')}}
				</p>
				<p>{{trans('terms.You may contact us by clicking')}} <a href="{{route('frontend.contact')}}">{{trans('terms.here')}}</a> {{trans('terms.for further inquiries or help.')}}
				</p>
				<p>{{trans('terms.You can modify your order within 1 hour of placing it, or until the store approves it.')}}
				</p>
				<p>{{trans('terms.Each store reserves the right to substitute some or all of the items in your order to the closest most similar product in case of product unavailability and you approved by choosing “ allow substitution” function while placing the order.')}}
				</p>
				<p>{{trans('terms.Goecolo.com does not take title to returned items and does not store products in its premises. At our discretion, a refund may be issued without requiring a return. In this situation, Goecolo.com does not take title to the refunded item.')}}
				</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Payment')}}</h3>
				<p>{{trans('terms.WHEN YOU ORDER FROM GOECOLO.COM SITE, YOU NEED TO PROVIDE YOUR CREDIT CARD PAYMENT INFORMATION FOR YOUR ORDER TO BE SUBMITTED. WHEN YOUR ORDER IS ASSEMBLED, GOECOLO INC. WILL CHARGE THE ACTUAL PRICE OF YOUR ORDER TO YOUR CREDIT CARD. IF YOUR PAYMENT IS REFUSED BY GOECOLO INC., THE ACQUIRER, ISSUER OR FINANCIAL INSTITUTION AND IN THE ABSENCE OF AN ALTERNATIVE ARRANGEMENT, YOUR ORDER WILL NOT BE COMPLETED AND GOECOLO INC. SHALL HAVE NO FURTHER OBLIGATION IN THIS REGARD. ALTHOUGH WE STRIVE TO HAVE OUR SUPPLIERS, RESELLERS AND RETAILERS PROVIDE THE MOST ACCURATE PRICES AND UPDATE THEIR PRICELISTS PERIODICALLY, REGULARLY AND WHEN NECESSARY; THE ACTUAL PRICE OF YOUR ORDER MAY DIFFER FROM THE ESTIMATED PRICE WHEN THE ORDER WAS PLACED DUE TO PRODUCT UNAVAILABILITY, SUBSTITUTIONS, PRICES, DISCOUNTS, PROMOTIONS AND COUPONS THAT MAY VARY FROM ONE PERIOD TO ANOTHER AND ACCORDING TO THOSE IN FORCE AT THE CHOSEN STORE, AND PRODUCTS SOLD BY WEIGHT OR CUT. IN GIVING US YOUR CREDIT CARD PAYMENT INFORMATION, YOU REPRESENT AND WARRANT THAT YOU ARE THE CARDHOLDER OF THAT CREDIT CARD.')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Communications')}}</h3>
				<p>{{trans('terms.Goecolo reserves the right to communicate with you via your account contact information regarding your orders and thereafter.')}} </p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Product Descriptions')}}</h3>
				<p>{{trans('terms.Goecolo.com attempts to be as accurate as possible. However, Goecolo.com does not warrant that product descriptions or other content of any Goecolo.com Service is accurate, complete, reliable, current, or error-free. If a product is offered by Goecolo.com itself is not as described, your sole remedy is to return it in unconsumed condition.')}}</p>


				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Pricing, Promotions, Discounts, Coupons and Applicable taxes')}}</h3>
				<p>{{trans('terms.All prices listed on Goecolo.com are in Canadian dollars, and are provided by a direct supplier, seller, or retail stores.')}}</p>

				<p>{{trans('terms.Despite our best efforts to update prices periodically (majority are done on a daily basis) with our suppliers, sellers and retail stores, a small number of the items in our pricelist may be mispriced due to the fact that they weren’t updated yet by supplier coinciding with your order.')}}</p>

				<p>{{trans('terms.If an item\'s correct price is higher than our stated price, we will, at our discretion, either contact you for instructions before shipping or cancel your order and notify you of such cancellation.')}}</p>
				<p>{{trans('terms.Coupons and other discounts must be in effect at the time of payment of your order and may not exceed the total cost of your order. Subject to applicable promotional terms and conditions.')}}</p>
				<p>{{trans('terms.Applicable Federal and Provincial taxes and deposits will be estimated when you place your order and the actual figures will be calculated based on the actual price of your order at the time of final payment.')}}</p>
				<p>{{trans('terms.Every order on our Site is subject to a minimum purchase requirement of $50.00 before taxes for assembly and delivery fees. Delivery fees are added to all orders, plus applicable taxes mentioned above. Fees may vary depending on your selection of designated store. Goecolo.com reserves the right to change these fees and the minimum purchase required. on the other hand you don’t need a minimum amount to compare prices.')}}
				</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Goecolo Software Terms')}}</h3>
				<p>{{trans('terms.In addition to these Conditions of Use, the terms found')}} <a href="{{route('frontend.terms',[App::getLocale()])}}">{{trans('terms.here')}}</a> 
				{{trans('terms.apply to any software (including any updates or upgrades to the software and any related documentation) that we make available to you from time to time for your use in connection with Goecolo.com Services (the "Goecolo Software").')}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Other Businesses')}}</h3>

				<p>{{trans('terms.Parties other than Goecolo.com operate stores, provide services, or sell product lines through the Goecolo.ca Services. In addition, we are not responsible for examining or evaluating, and we do not warrant the offerings of, any of these businesses or individuals or the content of their web sites. Goecolo.com does not assume any responsibility or liability for the actions, product, and content of all these and any other third parties.')}}
				</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Disclaimer of Warranties and Limitation of Liability')}}</h3>
				
				<p>{{trans("terms.THE LAWS OF CERTAIN JURISDICTIONS, INCLUDING QUEBEC'S CONSUMER PROTECTION ACT, DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR CONDITIONS OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE BELOW DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MIGHT NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS.")}}
				</p>
				<p>{{trans('terms.THE Goecolo.Com SERVICES AND ALL INFORMATION, CONTENT, MATERIALS, PRODUCTS (INCLUDING SOFTWARE) AND OTHER SERVICES INCLUDED ON OR OTHERWISE MADE AVAILABLE TO YOU THROUGH THE Goecolo.com SERVICES ARE PROVIDED BY Goecolo.com ON AN "AS IS" AND "AS AVAILABLE" BASIS, UNLESS OTHERWISE SPECIFIED IN WRITING. Goecolo.com MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED, AS TO THE OPERATION OF THE Goecolo.com SERVICES OR THE INFORMATION, CONTENT, MATERIALS, PRODUCTS (INCLUDING SOFTWARE) OR OTHER SERVICES INCLUDED ON OR OTHERWISE MADE AVAILABLE TO YOU THROUGH THE Goecolo.com SERVICES, UNLESS OTHERWISE SPECIFIED IN WRITING. YOU EXPRESSLY AGREE THAT YOUR USE OF THE Goecolo.com SERVICES IS AT YOUR SOLE RISK.')}}
				</p>
				<p>{{trans('terms.TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, Goecolo.com DISCLAIMS ALL WARRANTIES AND CONDITIONS, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF TITLE, MERCHANTABLE QUALITY AND FITNESS FOR A PARTICULAR PURPOSE. Goecolo.com DOES NOT WARRANT THAT THE Goecolo.com SERVICES, INFORMATION, CONTENT, MATERIALS, PRODUCTS (INCLUDING SOFTWARE) OR OTHER SERVICES INCLUDED ON OR OTHERWISE MADE AVAILABLE TO YOU THROUGH THE Goecolo.com SERVICES; ITS SERVERS, ELECTRONIC COMMUNICATIONS, OR E-MAIL SENT FROM Goecolo.com SERVICES ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. Goecolo.CA WILL NOT BE LIABLE FOR ANY DAMAGES OF ANY KIND ARISING FROM THE USE OF ANY Goecolo.com SERVICE OR FROM ANY INFORMATION, CONTENT, MATERIALS, PRODUCTS (INCLUDING SOFTWARE) OR SERVICES INCLUDED ON OR OTHERWISE MADE AVAILABLE TO YOU THROUGH ANY Goecolo.com SERVICE, INCLUDING, BUT NOT LIMITED TO, DIRECT, INDIRECT, INCIDENTAL, PUNITIVE, AND CONSEQUENTIAL DAMAGES, UNLESS OTHERWISE SPECIFIED IN WRITING.')}}
				</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Applicable Law and Disputes (for review)')}}</h3>
				<p>{{trans("terms.These Conditions of Use and any dispute of any sort that might arise between you and Goecolo.com shall be governed by the laws of the Province of Quebec, without reference to its conflict of law’s provisions, and the laws of Canada applicable therein, and any disputes will be submitted to the courts of competent jurisdiction of the District of Montreal (Quebec).")}}</p>

				<div class="gap gap-small"></div>
				<h3>{{trans('terms.Additional Goecolo Software Terms')}}</h3>
				<ul>
					<li><p><b>{{trans('terms.Use of the Goecolo Software.')}}</b> {{trans('terms.You may use Goecolo Software solely for purposes of enabling you to use and enjoy the Goecolo.com Services as provided by Goecolo.com, and as permitted by the Conditions of Use, these Software Terms and any Service Terms. You may not incorporate any portion of the Goecolo Software into your own programs or compile any portion of it in combination with your own programs, transfer it for use with another service, or sell, rent, lease, lend, loan, distribute or sub-license the Goecolo Software or otherwise assign any rights to the Goecolo Software in whole or in part. You may not use the Goecolo Software for any illegal purpose. We may cease providing any Goecolo Software and we may terminate your right to use any Goecolo Software at any time. Your rights to use the Goecolo Software will automatically terminate without notice from us if you fail to comply with any of these Software Terms, the Conditions of Use or any other Service Terms. Additional third-party terms contained within or distributed with certain Goecolo Software that are specifically identified in related documentation may apply to that Goecolo Software (or software incorporated with the Goecolo Software) and will govern the use of such software in the event of a conflict with these Conditions of Use. All software used in any Goecolo Service is the property of Goecolo.com or its software suppliers and protected by Canadian and international copyright laws.')}} </p></li>

					<li><p><b>{{trans('terms.Use of Third Party Services.')}}</b> {{trans('terms.When you use the Goecolo Software, you may also be using the services of one or more third parties, such as a wireless carrier or a mobile platform provider. Your use of these third party services may be subject to the separate policies, terms of use, and fees of these third parties.')}}</p></li>
					

					<li><p><b>{{trans('terms.No Reverse Engineering.')}}</b> {{trans('terms.You may not, and you will not encourage, assist or authorize any other person to copy, modify, reverse engineer, decompile or disassemble, or otherwise tamper with, the Goecolo Software, whether in whole or in part, or create any derivative works from or of the Goecolo Software.')}}</p></li>

					<li><p><b>{{trans('terms.Updates')}}</b> {{trans('terms.In order to keep the Goecolo Software up-to-date, we may offer automatic or manual updates at any time and without notice to you.')}}</p></li>
				</ul>
				<div class="gap gap-small gap-border"></div>
				<div class="gap gap-small"></div>
			</div>
		</div>
	</div>
</div>
@stop