<?php
/**
 * Shortcode of event attendees
 *
 * @category API
 * @package GnGn
 * @author Sarah Gregorio <sarahgregorio29@gmail.com>
 */
class Plugin_GnGn_Library_Shortcodes_Attendees extends Plugin_GnGn_Library_Shortcodes_Abstract
{
    /**
     * Generate parameters to the view
     *
     * @param array $params is static parameters from the shortcode
     *
     * @return array is dynamic parameters to _display() method.
     */
    public function _generate($params)
    {
       $all = $makati = $clark = 0;
        $filter = array(
            'sort' => "floor_name",
            'search' => ""
        );

        $event_id = $_GET['event_id'];
        $model_attendee = &GnGn::getInstance('Model_Attendee');
        $attendees = $model_attendee->get_all_attendees($event_id, $filter);

        if(!empty($attendees) && is_array($attendees)):
            $all = count($attendees);
            foreach ($attendees as $key => $value) {
                if((int)$value['office_branch'] === 1):
                    $clark++;
                else:
                    $makati++;
                endif;
            }
        endif;
        return compact('event_id', 'attendees', 'all', 'makati', 'clark');
    }

    /**
     * Display shortcode of event attendees
     *
     * @param array $params is generated parameters from _generate() method.
     */
    public function _display($params)
    {
      ?>
          <!-------------------------------------------ATTENDEES-LIST---------------------------------------------->

          <br/><br/><br/><br/><br/><br/>
          <div id="atnd-list">
          <img src="<?php printf('%s/%s', get_bloginfo('template_url'), 'img/list_header.png')?>" class="list_header">

          <br/><br/>
          <p class="tally">


          <form class="form-search fright" id="search_attendee_admin">
          &nbsp;&nbsp;<label>Search</label>&nbsp;&nbsp;
          <div class="input-append">
          <input type="text" class="span2 search-query" name="search" id="search">
          <input type="hidden" id="sort" name="sort">
          <input type="hidden" id="pid" name="pid" value="<?php printf('%s', $params['event_id'])?>">
          <button type="submit" class="btn"><i class="icon-search"></i></button>
          </div>
          </form>
          <div>
          <span class="count-list">Attendees Count: </span><span class="count-total"><?php printf('%d', $params['all'])?></span>&nbsp;&nbsp;
          <span class="count-list">Clark: </span><span class="count-total"><?php printf('%d', $params['clark'])?></span>&nbsp;&nbsp;
          <span class="count-list">Makati: </span><span class="count-total"><?php printf('%d', $params['makati'])?></span>&nbsp;&nbsp;
          </div>
          </p>

          <a href="<?php printf('%sedit.php?post_type=event', admin_url())?>"><u>BACK TO EVENT'S LIST</u></a> |
          <a href="<?php printf('%s/%s?event_id=%d&create=word', plugins_url(), 'gngn/helper/word.php', $params['event_id'])?>"><u>EXPORT TO WORD</u></a> |
          <a href="<?php printf('%s/%s?event_id=%d&create=excel', plugins_url(), 'gngn/helper/word.php', $params['event_id'])?>"><u>EXPORT TO EXCEL</u></a>
          <form class="form-search">

          <div class="atnd-list-table" id="attendace">
          <table class="table table-striped table-hover">
          <thead class="btn-info">
          <tr>
          <th><a href="#" class="arrow_admin" val="floor_name">Floor name ▲</a></th>
          <th><a href="#" class="arrow_admin" val="firstname">Full name</a></th>
          <th><a href="#" class="arrow_admin" val="office_branch">Office Branch</a></th>
          <th><a href="#" class="arrow_admin" val="email_address">Email Address</a></th>
          <th><a href="#" class="arrow_admin" val="contact_no">Contact Number</a></th>
          <th><a href="#" class="arrow_admin" val="office_branch">Action</a></th><!----->
          </tr>
          </thead>
          <tbody id="slist">
              <?php if(!empty($params['attendees']) && is_array($params['attendees'])):
                  $branch = array(1 =>'Clark', 'Makati', 'Guest');
                  foreach ($params['attendees'] as $key => $value) { ?>
                      <tr>
                          <td><?php printf('%s', $value['floor_name'])?></td> 
                          <td><?php printf('%s %s %s', $value['firstname'], $value['middlename'], $value['lastname'])?></td> 
                          <td><?php  printf('%s', $branch[$value['office_branch']])?></td>
                          <td><?php printf('%s', $value['email_address'])?></td>
                          <td><?php printf('%s', $value['contact_no'])?></td>
                          <td><?php printf('<a href="#" class="btn btn-danger btn-mini remove" att="%d"><i class="icon-white icon-trash"></i>Remove</a>', $value['attendee_id'])?></td>
                      </tr>
                  <?php } else: ?>
                      <tr>
                          <td colspan="6">No one registered yet.</td>
                      </tr>
              <?php endif; ?>
          </tbody>
          </table>
          </form>
          </div>
          <span class="clear"></span>
          <a href="<?php printf('%sedit.php?post_type=event', admin_url())?>"><u>BACK TO EVENT'S LIST</u></a>
          </div>
      <?php
    }
}