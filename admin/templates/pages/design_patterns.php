<?php

require '../../../vendor/autoload.php';

if(!perch_member_logged_in()){
	header("location:/");
}

perch_layout('header');
?>
<main>
	<div class="flow">
		<h2>Heading 2</h2>
		<h3>Heading 3</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="#">Vivamus accumsan</a> semper nisl, sit amet ultricies ante auctor sagittis. Aliquam suscipit vitae elit nec pretium. Suspendisse turpis ex, luctus id consequat ut, pretium ut justo. Morbi eget <strong>elementum quam</strong>, tincidunt fermentum lectus.</p>
		<p><a href="#" class="button">Standard Button</a> <a href="#" class="button primary">Primary Button</a> <a href="#" class="button secondary">Secondary Button</a> <a href="#" class="button border">Button Border</a> <a href="#" class="button border primary">Primary Border</a> <a href="#" class="button border secondary">Primary Border</a> <a href="#" class="button danger">Danger Button</a> <a href="#" class="button primary small">Small Button</a></p>
		<h3>Alerts</h3>
		<p class="alert">This is a standard alert.</p>
		<p class="alert error">This is an error.</p>
		<p class="alert success">This is a success message.</p>
		
		<section>
			<header>
				<h2>Section Heading</h2>
				<div>
					<select>
						<option>Options</option>
					</select>
					<div class="input-container">
						<input type="text" placeholder="Search" />
						<div class="results">
							<ul>
								<li><a href="#">Item 1</a></li>
								<li><a href="#">Item 2</a></li>
								<li><a href="#">Item 3</a></li>
								<li><a href="#">Item 4</a></li>
								<li><a href="#">Item 5</a></li>
							</ul>
						</div>
					</div>
					<input type="submit" class="button primary small" value="Go" />
				</div>
			</header>
			<article>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="#">Vivamus accumsan</a> semper nisl, sit amet ultricies ante auctor sagittis. Aliquam suscipit vitae elit nec pretium. Suspendisse turpis ex, luctus id consequat ut, pretium ut justo. Morbi eget elementum quam, tincidunt fermentum lectus.</p>
				<ul>
					<li>This is a list of items</li>
					<li>It could be a list of links</li>
					<li>Or just information</li>
				</ul>
				<p class="section-heading">All Contacts</p>
				<div class="grid contacts flow">
					<div class="row heading">
						<div class="th">
							<h3>Name</h3>
						</div>
						<div class="th">
							<h3>Address</h3>
						</div>
						<div class="th">
							<h3>Contact</h3>
						</div>
						<div class="th">
							<h3>Tags</h3>
						</div>
						<div class="th">
						
						</div>
					</div>
					<div class="row">
						<div class="td">
							<a href="/contacts/edit-contact?id=2"><span class="material-symbols-outlined">person</span>Jack Barber</a>
						</div>
						<div class="td">
							<p><span class="material-symbols-outlined">home</span>53 Upgang Lane</p>
						</div>
						<div class="td">
							<p><span class="material-symbols-outlined">check_circle</span>07980131289</p>
							<p><span class="material-symbols-outlined">email</span>jack@jackbarber.co.uk</p>
						</div>
						<div class="td">
							<ul class="pills">
								<li><span class="material-symbols-outlined">check_circle</span>member</li>
								<li><span class="material-symbols-outlined">check_circle</span>elder</li>
								<li><span class="material-symbols-outlined">check_circle</span>whitby</li>
								<li><span class="material-symbols-outlined">check_circle</span>barber</li>
							</ul>
						</div>
						<div class="td">
							<input type="checkbox" class="contact_select" name="select_2" data-contact="2" />
						</div>
					</div>
					<div class="row">
						<div class="td">
							<a href="/contacts/edit-contact?id=2"><span class="material-symbols-outlined">person</span>Jack Barber</a>
						</div>
						<div class="td">
							<p><span class="material-symbols-outlined">home</span>53 Upgang Lane</p>
						</div>
						<div class="td">
							<p><span class="material-symbols-outlined">check_circle</span>07980131289</p>
							<p><span class="material-symbols-outlined">email</span>jack@jackbarber.co.uk</p>
						</div>
						<div class="td">
							<ul class="pills">
								<li><span class="material-symbols-outlined">check_circle</span>member</li>
								<li><span class="material-symbols-outlined">check_circle</span>elder</li>
								<li><span class="material-symbols-outlined">check_circle</span>whitby</li>
								<li><span class="material-symbols-outlined">check_circle</span>barber</li>
							</ul>
						</div>
						<div class="td">
							<input type="checkbox" class="contact_select" name="select_2" data-contact="2" />
						</div>
					</div>
				</div>
			</article>
			<footer>
				<a class="button primary" href="#">Button</a>
			</footer>
		</section>
		
		<div class="section-grid">
		
			<div>
				<section>
					<form>
						<header>
							<h2>Section Heading</h2>
							<div>
		
							</div>
						</header>
						<article>
							<div>
								<label>Text</label>
								<input type="text" name="text" />
							</div>
							<div>
								<label>Email</label>
								<input type="email" name="email" />
							</div>
							<div>
								<label>Phone</label>
								<input type="tel" name="text" />
							</div>
							<div>
								<label>Select</label>
								<select>
									<option>Option 1</option>
									<option>Option 2</option>
								</select>
							</div>
							<div>
								<label>Radio</label>
								<label><input type="radio" name="radio" value="item 1"> Item 1</label>
								<label><input type="radio" name="radio" value="item 2"> Item 2</label>
							</div>
							<div>
								<label>Textarea</label>
								<textarea class="redactor" name="text"></textarea>
							</div>
						</article>
						<footer>
							<a class="button primary" href="#">Button</a>
						</footer>
					</form>
				</section>
			</div>
			
			<div>
				<section>
					<header>
						<h2>Contact Cards</h2>
						<div>
	
						</div>
					</header>
					<article>
						<ul class="cards">
							<li class="flow">
								<span class="material-symbols-outlined">person</span>
								<h3>Jack Barber</h3>
								<p>01947 878108<br />jack@jackbarber.co.uk</p>
								<a href="#" class="button primary small">Button</a>
							</li>
							<li class="flow">
								<span class="material-symbols-outlined">person</span>
								<h3>Jack Barber</h3>
								<p>01947 878108<br />jack@jackbarber.co.uk</p>
								<a href="#" class="button primary small">Button</a>
							</li>
							<li class="flow">
								<span class="material-symbols-outlined">person</span>
								<h3>Jack Barber</h3>
								<p>01947 878108<br />jack@jackbarber.co.uk</p>
								<a href="#" class="button primary small">Button</a>
							</li>
							<li class="flow">
								<span class="material-symbols-outlined">person</span>
								<h3>Jack Barber</h3>
								<p>01947 878108<br />jack@jackbarber.co.uk</p>
								<a href="#" class="button primary small">Button</a>
							</li>
							<li class="flow">
								<span class="material-symbols-outlined">person</span>
								<h3>Jack Barber</h3>
								<p>01947 878108<br />jack@jackbarber.co.uk</p>
								<a href="#" class="button primary small">Button</a>
							</li>
						</ul>
					</article>
					<footer>
						<a class="button primary" href="#">Button</a>
					</footer>
				</section>
			</div>
		
		</div>
		
		<section>
			<header>
				<h2>List of Items</h2>
				<div>
					<select>
						<option>Options</option>
					</select>
					<div class="input-container">
						<input type="text" placeholder="Search" />
						<div class="results">
							<ul>
								<li><a href="#">Item 1</a></li>
								<li><a href="#">Item 2</a></li>
								<li><a href="#">Item 3</a></li>
								<li><a href="#">Item 4</a></li>
								<li><a href="#">Item 5</a></li>
							</ul>
						</div>
					</div>
					<input type="submit" class="button primary small" value="Go" />
				</div>
			</header>
			<article>
				<p class="section-heading">All Items</p>
				<ul class="list">
					<li>
						<h3>Item Heading</h3>
						<p>This is an item description with more details.</p>
						<a href="#" class="button primary small">Button</a>
					</li>
					<li>
						<h3>Item Heading</h3>
						<p>This is an item description with more details.</p>
						<a href="#" class="button primary small">Button</a>
					</li>
					<li>
						<h3>Item Heading</h3>
						<p>This is an item description with more details.</p>
						<a href="#" class="button primary small">Button</a>
					</li>
				</ul>
			</article>
			<footer>
				<a class="button primary" href="#">Pop-up Window</a>
			</footer>
		</section>
		
		<div class="panel flow">
			<h3>Heading 3</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="#">Vivamus accumsan</a> semper nisl, sit amet ultricies ante auctor sagittis. Aliquam suscipit vitae elit nec pretium. Suspendisse turpis ex, luctus id consequat ut, pretium ut justo. Morbi eget <strong>elementum quam</strong>, tincidunt fermentum lectus.</p>
			<ul>
				<li><a href="#">Another Item</a></li>
				<li><a href="#" class="warning">Dangerous Item</a></li>
			</ul>
		</div>
		
	</div>
</main>
<?php perch_layout('footer'); ?>