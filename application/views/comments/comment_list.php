		<?php foreach ($comments as $comment) : ?>
	
			<li id="comment-<?php echo $comment->comment_id; ?>">
				<article>
					<footer class="commenter_info">
						<img src="<?php echo base_url() . 'uploads/avatars/' . $comment->avatar; ?>" alt="<?php echo $comment->first_name; ?>'s Profile Picture" width="48" height="48" /><br />
						<?php 
							// show the time, must be converted to a function!
							$time = strtotime($comment->datetime);
							echo "<time datetime='".date('Y-m-d H:i', $time)."'>";
							echo date("d ", $time);
							$month = date("m", $time);
							switch ($month)
							{
								case 1: $month = "Januari"; break;
								case 2: $month = "Februari"; break;
								case 3: $month = "Maret"; break;
								case 4: $month = "April"; break;
								case 5: $month = "Mei"; break;
								case 6: $month = "Juni"; break;
								case 7: $month = "Juli"; break;
								case 8: $month = "Agustus"; break;
								case 9: $month = "September"; break;
								case 10: $month = "Oktober"; break;
								case 11: $month = "November"; break;
								case 12: $month = "Desember"; break;
							}
							echo $month." ";
							echo date("Y", $time);
							echo "<br />";
							echo "<strong>" . date("h:i a", $time) . "</strong>";
							echo "</time>";
						?>
					</footer>
					<?php if ($comment->commenter_response == 'happy') : ?>
						<img class="smiley" src="<?php echo base_url(); ?>images/smileys/happy.gif" alt="Wajah Senang" width="64" height="64" />
					<?php elseif ($comment->commenter_response == 'sad') : ?>
						<img class="smiley" src="<?php echo base_url(); ?>images/smileys/sad.gif" alt="Wajah Marah" width="64" height="64" />
					<?php else : ?>
						<img class="smiley" src="<?php echo base_url(); ?>images/smileys/indifferent.gif" alt="Wajah Biasa Saja" width="64" height="64" />
					<?php endif; ?>
					<div class="comment_content">
                                <p class="commenter_name">
                                    Menurut <strong><?php echo anchor('members/profile/' . $comment->user_id, $comment->first_name); ?></strong> di <?php echo $comment->address; ?>
						</p>
						<?php 
						// converting newlines to paragraph
						$comment_content = nl2br($comment->commenter_comment);
						$comment_content = explode("<br />", $comment_content);
						$comment_content = array_filter($comment_content);
						echo "<p>" . implode("</p><p>", $comment_content) . "</p>";
						?>
						
					</div>
				</article>
				<div class="comment_action">
				
					<?php if ($this->ion_auth->logged_in()) : ?>
					<a class="add_comment" title="Komentari komentar ini" href="#" name="<?php echo $comment->comment_id; ?>">Komentari</a>
						<?php if ($this->ion_auth->get_user()->id == $comment->commenter_id) : ?>
							<?php echo anchor('comments/confirm_delete/' . $comment->comment_id, 'Hapus'); ?>
						<?php endif; ?>
					<?php endif; ?>
					
					<p class="comment_vote">
					<?php if ($this->ion_auth->logged_in()
							&& $this->comment_model->voting_check($this->ion_auth->get_user()->id, $comment->comment_id) == 'up') : ?>
							
						<span class="vote_up_inactive"><span>Voted Up</span></span>
						<strong style="color:<?php echo $comment->vote_count > 0 ? '#4eaf4b' : ($comment->vote_count < 0 ? '#de5352' : '#444'); ?>"><?php echo $comment->vote_count; ?></strong>
						<?php echo anchor('comments/vote/down/' . $comment->comment_id, '<span>Unvote</span>', array('class' => 'vote_down', 'title' => 'Batalkan vote')); ?>
								
						<?php elseif ($this->ion_auth->logged_in()
									&& $this->comment_model->voting_check($this->ion_auth->get_user()->id, $comment->comment_id) == 'down') : ?>
							
						<?php echo anchor('comments/vote/up/' . $comment->comment_id, '<span>Unvote</span>', array('class' => 'vote_up', 'title' => 'Batalkan vote')); ?>
						<strong style="color:<?php echo $comment->vote_count > 0 ? '#4eaf4b' : ($comment->vote_count < 0 ? '#de5352' : '#444'); ?>"><?php echo $comment->vote_count; ?></strong>
						<span class="vote_down_inactive"><span>Voted Down</span></span>
								
					<?php else : ?>
							
						<?php echo anchor('comments/vote/up/' . $comment->comment_id, '<span>Vote Up</span>', array('class' => 'vote_up', 'title' => 'Vote Up')); ?>
						<strong style="color:<?php echo $comment->vote_count > 0 ? '#4eaf4b' : ($comment->vote_count < 0 ? '#de5352' : '#444'); ?>"><?php echo $comment->vote_count; ?></strong>
						<?php echo anchor('comments/vote/down/' . $comment->comment_id, '<span>Vote Down</span>', array('class' => 'vote_down', 'title' => 'Vote Down')); ?>
								
					<?php endif; ?>
					</p>
					
				</div>
				<?php
					// querying the database form here, not good :(
					$this->db->select('*')->from('comments')->where('comment_parent_id', $comment->comment_id)->join('meta', 'meta.user_id = comments.commenter_id')->order_by('datetime', 'asc'); 
					$child_comments = $this->db->get();
					?>
					<?php if ($child_comments->num_rows() > 0 ) : ?>
					
					<ul class="replies">
					
						<?php foreach ($child_comments->result() as $child_comment) : ?>
						
							<li id="comment-<?php echo $child_comment->comment_id; ?>">
								<article>
									<?php 
									// converting newlines to paragraph
									$child_comment_content = nl2br($child_comment->commenter_comment);
									$child_comment_content = explode("<br />", $child_comment_content);
									$child_comment_content = array_filter($child_comment_content);
									echo "<p>" . implode("</p><p>", $child_comment_content) . "</p>";
									?>
									<?php if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->id == $child_comment->commenter_id) : ?>
										<p><?php echo anchor('comments/confirm_delete/' . $child_comment->comment_id, 'Hapus'); ?></p>
									<?php endif; ?>
									<footer>
										<?php echo anchor('members/profile/' . $child_comment->commenter_id, '<strong>' . $child_comment->first_name . '</strong>'); ?>
										&mdash;
										<?php 
											// show the time, must be converted to a function!
											$time = strtotime($child_comment->datetime);
											echo "<time datetime='".date('Y-m-d H:i', $time)."'>";
											echo date("d ", $time);
											$month = date("m", $time);
											switch ($month)
											{
												case 1: $month = "Januari"; break;
												case 2: $month = "Februari"; break;
												case 3: $month = "Maret"; break;
												case 4: $month = "April"; break;
												case 5: $month = "Mei"; break;
												case 6: $month = "Juni"; break;
												case 7: $month = "Juli"; break;
												case 8: $month = "Agustus"; break;
												case 9: $month = "September"; break;
												case 10: $month = "Oktober"; break;
												case 11: $month = "November"; break;
												case 12: $month = "Desember"; break;
											}
											echo $month." ";
											echo date("Y ", $time);
											echo date("h:i a", $time);
											echo "</time>";
										?>
									</footer>
								</article>
							</li>
						
						<?php endforeach; ?>
					
					</ul>
					
					<?php endif; ?>
			</li>
	
		<?php endforeach; ?>
	