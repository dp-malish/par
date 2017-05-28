CREATE TABLE IF NOT EXISTS content(
  id int(11) NOT NULL AUTO_INCREMENT,
  link varchar(255) NOT NULL,
  link_name varchar(255) NOT NULL,
  menu tinyint(4),
  heading varchar(255),#рубрика
  category varchar(255),#категория
  link_turn tinyint(4),
  title varchar(255) NOT NULL,
  meta_d varchar(255) NOT NULL,
  meta_k varchar(255) NOT NULL,
  caption varchar(255) NOT NULL,
  img_s varchar(255),
  img_alt_s varchar(255),
  img_title_s varchar(255),
  short_text text,
  img varchar(255),
  img_alt varchar(255),
  img_title varchar(255),
  left_text text,
  right_text text,
  full_text text NOT NULL,
  data int(11),
  views int(11) DEFAULT 13,
  comment int(11),
  donor int(11),

  PRIMARY KEY (id),
  UNIQUE KEY link(link),
  KEY (heading,category)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


#######################################
CREATE TABLE IF NOT EXISTS sites_donor(
  id int(11) NOT NULL AUTO_INCREMENT,
  site varchar(100) NOT NULL,
  `paginator_link` varchar(255),
  paginator_img_s varchar(255),
  `paginator_short_text` varchar(255),
  `paginator_short_text_del_link` varchar(255),
  PRIMARY KEY (id),
  UNIQUE KEY link(site)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

#INSERT INTO `sites_donor` (`id`, `site`) VALUES (NULL, 'http://klopotenko.com');
#UPDATE `sites_donor` SET `paginator_link` = 'main > div.row > div.col-xs-12 > article > header > h2 > a', `paginator_img` = 'main > div.row > div.col-xs-12 > article > figure > a > img' WHERE `sites_donor`.`id` = 1;

CREATE TABLE IF NOT EXISTS sites_donor_options(
  id int(11) NOT NULL AUTO_INCREMENT,
  id_site int(11) NOT NULL,
  rubrika varchar(255),#рубрика
  rubrika_name varchar(255),#рубрика
  category varchar(255),#категория
  category_name varchar(255),#категория
  page varchar(255),
  page_end varchar(255),

  max_page int(5),
  data date,

  img_s_dir varchar(255),
  img_s_table varchar(255),


  PRIMARY KEY (id)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `sites_donor_options` (`id`, `id_site`, `rubrika`, `rubrika_name`, `category`, `category_name`, `page`, `page_end`, `max_page`, `data`) VALUES
  (1, 1, NULL, NULL, '/category/12zakysky/', 'закуски', 'page/', '/', 3, '2017-05-24'),
  (2, 1, NULL, NULL, '/category/13salaty/', 'салаты', 'page/', '/', 3, '2017-05-24'),
  (3, 1, NULL, NULL, '/category/14soups/', 'супы', 'page/', '/', 2, '2017-05-24');


CREATE TABLE IF NOT EXISTS sites_donor_link(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  id_opt int(11),
  `site` varchar(100),
  `link_donor` varchar(255) NOT NULL,
  `img_s_donor` varchar(255),
  `short_text_donor` text,
  img_s_table varchar(255),# таблица картинки маленькой
  `img_s` varchar(255),
  `img_s_dir` varchar(255),
  `img_s_name` varchar(255),
  `rubrika` varchar(255),
  `category` varchar(255),
  `img` varchar(255),
  `data` date,
  PRIMARY KEY (id),
  UNIQUE KEY link_donor(link_donor)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

UPDATE sites_donor_link SET img_s_dir=NULL;
#######################################