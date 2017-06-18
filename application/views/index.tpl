<!DOCTYPE html>
<head>
	<title> 范儿 </title>
	<meta name="keywords" content="你每翻开的一张卡片，都是一个故事！" />
	<meta name="description" content="你每翻开的一张卡片，都是一个故事！" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="application/views/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="application/views/css/magnific-popup.css" rel="stylesheet"> 
	<link href="application/views/css/templatemo_style.css" rel="stylesheet" type="text/css">
	<link href="application/views/css/search.css" rel="fieldset" type="text/css">
</head>
<body>
	<div class="main-container">
		<nav class="main-nav">
			<div id="logo" class="left"><a href="#"></a>Fanner</div>
			<ul class="nav right center-text">
				<!--<li class="btn active">Home</li>
				<li class="btn"><a href="">About</a></li>
				<li class="btn"><a href="">Awards</a></li>				
				<li class="btn"><a href="">Contact</a></li>
				<li class="btn"><a rel="" href="#">External</a></li>-->
				<form method="get" id="searchform" action="index">
					<fieldset class="search">
						<input type="text" class="box" name="word" id="word" class="inputText" placeholder="你喜欢的歌或歌手" x-webkit-speech>
          				<button class="btn" title="SEARCH"> </button>
    				</fieldset>
				</form>
			</ul>
		</nav>
		<div class="content-container">
			<header>
			<h2 class="center-text">你每翻开的一张卡片，都是一个故事！</h2>
			</header>
		<div id="portfolio-content" class="center-text">
			<div class="portfolio-page" id="page-1">
	<ul>
	{foreach from= $content item=value}	
			<div class="portfolio-group">
					<a class="portfolio-item">
						<img src="{$value['commenter_avatar']}" alt="image 1">
						<div class="detail">
							<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$value['commenter_content']}</p>
							<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-----来自用户'{$value['commenter_nickname']}'在歌手{$value['song_singer']}《{$value['song_name']}》下的留言</p>
						</div>
					</a>				
			</div>
		{/foreach}
			</div> <!-- page 3 -->		
			<div class="pagination">
				<ul class="nav">
					{if $smarty.get.page > 0 }
						<li> <a href="/index?page={$smarty.get.page-1}">P</a> </li>
					{/if}
					{assign var=i value=0}
						{section name=total loop=10}
						<li class='active'>{assign var=i value=$i+1}<a href="/index?page={$i}">{$i}</a></li>
					{/section}
                    {if $total > 10 }
                        <li><a href="/index?page={$smarty.get.page+1}">N</a></li>
                    {/if}
				</ul>
			</div>
		</div>
	</div>	<!-- /.content-container -->	
    
		<footer>
			<p>Copyright &copy; 2017 </p>
			<div class="social right">
				<a href="#"><i class="fa fa-facebook"></i></a>
				<a href="#"><i class="fa fa-twitter"></i></a>
				<a href="#"><i class="fa fa-google-plus"></i></a>
				<a href="#"><i class="fa fa-dribbble"></i></a>
				<a href="#"><i class="fa fa-instagram"></i></a>
				<a href="#"><i class="fa fa-linkedin"></i></a>
			</div>
		</footer>
	</div>
	<script type="text/javascript" src="application/views/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="application/views/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="application/views/js/modernizr.2.5.3.min.js"></script>
	<script type="text/javascript" src="application/views/js/jquery.magnific-popup.min.js"></script> 
	<script type="text/javascript" src="application/views/js/templatemo_script.js"></script>
	<!--<script type="text/javascript">
		$(function () {
			$('.pagination li').click(changePage);
			$('.portfolio-item').magnificPopup({ 
				type: 'image',
				gallery:{
					enabled:true
				}
			});
		});
	</script>	
</body>
</html>
