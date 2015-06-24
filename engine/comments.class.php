<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadgeh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
**/

defined('security') or exit('Direct Access to this location is not allowed.');

class comments 
{
    public $type;
    public $link;
    public $action;
    public $options;
    public $comments;
    public $comments_count;
    public $message;
    public $total;

    /**
    * This var show comment posing status.if a comment posted and added to database it will be true 
    * else if comment posting was unsuccessful it will be false else if there is no comment to post 
    * it is null (by default)
	*
	* @since 1.1
	*/

    public $comment_posted = null;
    public $start = 1;
    public $length = null;

    public function __construct($type, $link, $action)
	{
		$this->type = alphabet($type);
		$this->link = intval($link);
		$this->action = $action;
		$this->options();
	}

	public function options()
	{
		if (!$this->options = get_cache('options-comments'))
		{
			global $d;
			$d->query("SELECT `option_value` FROM `#__options` WHERE `option_name`='comments' LIMIT 1");
			$result = $d->fetch();
			$d->free_result();
			$this->options = maybe_unserialize($result['option_value']);
			set_cache('options-comments', $this->options);
		}
		return $this->options;
	}

	/**
	 * Get Commets
	 *
	 * This function make a query for final parsing and get comments data and real comments count
	 *
	 * @return void
	 * @since 1.0
	 *
	 **/
    public function query()
	{
		if ( !isset( $this->comments_count ) || !is_array( $this->comments ) || ! count($this->comments) ) {

			global $d;

			$limit = null;

			if( is_int( $this->start ) ){

				$limit = " LIMIT ". $this->start . " ";

				if ( is_int( $this->length ) && $this->length > 0) {
					$limit .= ", ". $this->length . " ";
				}
			}

			$query = "
				SELECT c.*, m.member_avatar, m.member_group, m.member_name
				FROM #__comments AS c
				LEFT JOIN #__members AS m ON (m.member_id = c.comment_member_id)
				WHERE c.comment_type='".$d->escape_string($this->type)."' AND c.comment_link='".intval($this->link)."'".($this->options['approve']==1? " AND (c.comment_approve='1'".(member == 1? " OR c.comment_member_id='".member_id."'" : " OR c.comment_author_ip='".$d->escape_string(get_ip())."'").")" : null).
				"GROUP BY c.comment_id
				ORDER BY c.comment_id ASC".
				$limit ;

			$this->comments = $d->get_row($query);
			$this->comments_count = is_array($this->comments)? count($this->comments) : 0;
			unset($query);

		}

	}

	/**
	 * Return Total Comments Count
	 *
	 * @return int An integer that shows total comments
	 * @since 1.1
	 **/
	public function get_total_comments()
	{
		global $d;

		if( empty( $this->total ) || !is_int( $this->total ) ){

			$query = "
			SELECT COUNT(*) AS count FROM #__comments
			WHERE comment_type='".$d->escape_string($this->type)."' AND
			comment_link='".intval($this->link)."'".
			($this->options['approve']==1? " AND (comment_approve='1'".(member == 1? " OR comment_member_id='".member_id."'" : " OR comment_author_ip='".$d->escape_string(get_ip())."'").")" : null);

			$data = $d->fetch( $query , 'assoc' , true );
			$this->total = $data['count'];

		}

		return $this->total;
	}

    public function build()
	{
		global $member, $options, $tpl, $member_groups;
	
		if (member == 1)
		{
			$member = member::is('info');
		}
		
		// save a new comment
		$com = get_param($_POST, 'comment');
		if (is_array($com) && count($com))
		{
			$this->post($com);
		}
		unset($com);

		$this->query();
		
		require_once(engine_dir.'captcha.function.php');
		require_once(engine_dir.'editor.function.php');
		require_once(engine_dir.'bbcode.class.php');

		$bbcode = new bbcode();
		$comments = array();
		$form = array();

		// $file = get_tpl();

		$itpl = new template('comments.tpl', template_dir );

		($hook = get_hook('comments_build_start'))? eval($hook) : null;

		if (is_array($this->comments) && count($this->comments))
		{
			foreach($this->comments as $com)
			{
				$array = array(
					'{odd-even}' => odd_even(),
					'{member-id}' => $com['comment_member_id'],
					'{member-avatar}' => member::avatar($com['member_avatar']),
					'{member-name}' => $com['member_name'],
					'{id}' => $com['comment_id'],
					'{author}' => $com['comment_author'],
					'{author-email}' => $com['comment_author_email'],
					'{author-url}' => $com['comment_author_url'],
					'{author-ip}' => $com['comment_author_ip'],
					'{date}' => jdate('l j F Y ساعت g:i A', $com['comment_date']),
					'{past-time}' => get_past_time($com['comment_date']),
					'{text}' => '<a name="comment-'.$com['comment_id'].'"></a>'.nl2br($bbcode->parse($com['comment_text'])),
					'{answer-author}' => $com['comment_answer_author'],
					'{answer}' => empty($com['comment_answer'])? null : nl2br($bbcode->parse($com['comment_answer'])),
					'{language}' => $com['comment_language'],
				);

				if (isset($member_groups[$com['member_group']]) && $member_groups[$com['member_group']]['group_admin'] == 1)
				{
					$array['[author-admin]'] = null;
					$array['[/author-admin]'] = null;
					$array['replace']['#\\[not-author-admin\\](.*?)\\[/not-author-admin\\]#s'] = '';
				}
				else
				{
					$array['[not-author-admin]'] = null;
					$array['[/not-author-admin]'] = null;
					$array['replace']['#\\[author-admin\\](.*?)\\[/author-admin\\]#s'] = '';
				}
				
				if (!empty($com['comment_author_url']))
				{
					$array['[author-url]'] = null;
					$array['[/author-url]'] = null;
				}
				else
				{
					$array['replace']['#\\[author-url\\](.*?)\\[/author-url\\]#s'] = '';
				}
				
				if (!empty($com['comment_answer']))
				{
					$array['[answer]'] = null;
					$array['[/answer]'] = null;
				}
				else
				{
					$array['replace']['#\\[answer\\](.*?)\\[/answer\\]#s'] = '';
				}
				
				if ($this->options['approve'] == 0 || $com['comment_approve'] == 1)
				{
					$array['[approve]'] = null;
					$array['[/approve]'] = null;
					$array['replace']['#\\[not-approve\\](.*?)\\[/not-approve\\]#s'] = '';
				}
				else
				{
					$array['[not-approve]'] = null;
					$array['[/not-approve]'] = null;
					$array['replace']['#\\[approve\\](.*?)\\[/approve\\]#s'] = '';
				}
				
				$array['replace']['|{date format=[\'"](.+?)[\'"]}|es'] = 'jdate("\\1", "'.$com['comment_date'].'")';
				$itpl->add_for('comments', $array);
			}
		}

		$itpl->assign(array(
			'{message}' => empty($this->message)? null : message($this->message, 'error'),
			'{action}' => $this->action,
			'{name}' => member? (!empty($member['member_alias'])? $member['member_alias'] : member_name) : null,
			'{email}' => isset($member['member_email']) && !empty($member['member_email'])? $member['member_email'] : null,
			'{url}' => isset($member['member_web']) && !empty($member['member_web'])? $member['member_web'] : 'http://',
			'{comments-count}' => $this->comments_count,
			'{link}' => $this->link,
			'{wysiwyg-textarea}' => $this->options['editor']==1? wysiwyg_textarea('comment[text]', isset($_POST['comment']['text'])? htmlencode($_POST['comment']['text']) : null, 'BBcode') : '<textarea name="comment[text]" id="comment-text" cols="45" rows="5">'.(isset($_POST['comment']['text'])? htmlencode($_POST['comment']['text']) : null).'</textarea>',
			'{captcha}' => create_captcha('comment')
		));

		if ($this->comments_count <= 0)
		{
			$itpl->assign(array(
				'[no-comments]' => null,
				'[/no-comments]' => null,
			));
			$itpl->block('#\\[have-comments\\](.*?)\\[/have-comments\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[have-comments]' => null,
				'[/have-comments]' => null,
			));
			$itpl->block('#\\[no-comments\\](.*?)\\[/no-comments\\]#s', '');
		}
		
		if (!empty($this->message))
		{
			$itpl->assign(array(
				'[message]' => null,
				'[/message]' => null,
			));
		}
		else
		{
			$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
		}

		if ($this->options['post-guest'] == 1 || member == 1)
		{
			$itpl->assign(array(
				'[post]' => null,
				'[/post]' => null,
			));
			$itpl->block('#\\[not-post\\](.*?)\\[/not-post\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'{message}' => message('فقط کاربران عضو می توانند نظر ارسال کنند!', 'error'),
				'[not-post]' => null,
				'[/not-post]' => null,
			));
			$itpl->block('#\\[post\\](.*?)\\[/post\\]#s', '');
		}
		
		if ($this->options['editor'] == 1)
		{
			$itpl->assign(array(
				'[editor]' => null,
				'[/editor]' => null,
			));
			$itpl->block('#\\[not-editor\\](.*?)\\[/not-editor\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[not-editor]' => null,
				'[/not-editor]' => null,
			));
			$itpl->block('#\\[editor\\](.*?)\\[/editor\\]#s', '');
		}

		($hook = get_hook('comments_build_end'))? eval($hook) : null;

		$tpl->assign('{content}', $itpl->get_var(), 'add');
		unset($bbcode, $comments, $form, $com, $post_name, $itpl);
	}

    public function post($post)
	{
		global $member;
		
		require_once(engine_dir.'captcha.function.php');
		$message = array();

		$this->comment_posted = false;
		
		($hook = get_hook('comments_post_start'))? eval($hook) : null;

		if (member == 1)
		{
			$member = member::is('info');

			$post['name'] = !empty($member['member_alias'])? $member['member_alias'] : member_name;
			$post['email'] = $member['member_email'];
			$post['url'] = !empty($member['member_web'])? $member['member_web'] : null;
		}
		else
		{
			$post['name'] = isset($post['name'])? htmlencode($post['name']) : null;
			$post['email'] = isset($post['email'])? nohtml($post['email']) : null;
			$post['url'] = isset($post['url'])? nohtml($post['url']) : null;
		}

		$post['text'] = isset($post['text'])? htmlencode($post['text']) : null;
		$post['captcha'] = isset($post['captcha'])? nohtml($post['captcha']) : null;
		
		if (empty($this->type) || !isnum($this->link) || $this->link <= 0)
		{
			$message[] = 'اطلاعات شناسایی نظر معتبر نمی باشد!';
		}

		if ($this->options['post-guest'] == 0 && member == 0)
		{
			$message[] = 'کاربران غیر عضو اجازه ارسال نظر را ندارند';
		}

		if ($post['name'] == '')
		{
			$message[] = 'نام خود را وارد نکرده اید!';
		}

		if ($this->options['email'] == 1)
		{
			if (empty($post['email']) || !validate_email($post['email']))
			{
				$message[] = 'ایمیل وارد شده صحیح نیست!';
			}
		}

		if ($post['url'] != '' && $post['url'] != 'http://' && !validate_url($post['url']))
		{
			$message[] = 'آدرس وبسایت شما معتبر نمی باشد!';
		}
		
		if (empty($post['text']))
		{
			$message[] = 'متن نظر خود را ننوشته اید!';
		}
		else
		{
			$text = nohtml($post['text']);
			if (apadana_strlen($text) < 5)
			{
				$message[] = 'نظر شما خیلی کوتاه است حداقل باید 5 حرف باشد!';
			}
			elseif (apadana_strlen($text) > $this->options['limit'])
			{
				$message[] = 'نظر شما '.apadana_strlen($text).' حرف است، حداکثر تعداد حروف مجاز '.$this->options['limit'].' حرف می باشد!';
			}
		}

		if (!validate_captcha('comment', $post['captcha']))
		{
			$message[] = 'کد امنیتی را صحیح وارد نکرده اید!';
		}

		if (count($message))
		{
			$this->message = implode('<br/>', $message);
		}
		else
		{
			global $d;
			
			if ($post['url'] == 'http://')
			{
				$post['url'] = null;
			}
			
			if (!validate_email($post['email']))
			{
				$post['email'] = null;
			}

			#$post['text'] = template_off($post['text']);
			$post['text'] = str_replace('{', '&#x7B;', $post['text']);
			$post['text'] = preg_replace('#\s{2,}#', ' ', $post['text']);

			$arr = array(
				'comment_type' => $this->type,
				'comment_link' => intval($this->link),
				'comment_author' => $post['name'],
				'comment_author_email' => $post['email'],
				'comment_author_url' => $post['url'],
				'comment_author_ip' => get_ip(),
				'comment_date' => time(),
				'comment_text' => $post['text'],
				'comment_member_id' => member_id,
				'comment_approve' => (group_super_admin || member::check_admin_page_access("comments") || $this->options['approve'] == 0) ? 1 : 0
			);

			$d->insert('comments', $arr);
			if ($d->affected_rows())
			{
				unset($_POST['comment']);
				remove_captcha('comment');
				remove_cache('comments', true);

				$this->comment_posted = true;

				($hook = get_hook('comments_post_save'))? eval($hook) : null;

				/**
				* @since 1.1 
				*/
				($hook = get_hook('comments_post_save_'. $this->type))? eval($hook) : null;
			}
			else
			{
				$this->message = 'در ذخیره نظر خطایی رخ داده مجدد تلاش کنید!';
			}
		}

		($hook = get_hook('comments_post_end'))? eval($hook) : null;

		unset($text, $message, $post, $arr);
	}
	public function set_limits($start = 1 , $length = null)
	{
		if( is_int( $start ) )
			$this->start = $start;
		if ( ! is_null($length) && is_int( $length ) ) 
			$this->length = $length;
	}

}
