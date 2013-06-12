<?php
/**
 *
 * Copyright (c) 2013 Jorge López Pérez <jorge@adobo.org>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

class hide_non_carddav_addressbooks extends rcube_plugin
{
	public function init()
	{
		$this->add_hook('addressbooks_list', array($this, 'hide_other'));

		// Do not autocomplete from non-carddav addressbooks
		$this->remove_autocomplete_entries();

	}

	public function hide_other($args)
	{
		$new_sources = array();
		foreach ($args['sources'] as $id => $data) {
			if (substr($id, 0, 8) == 'carddav_') {
				$new_sources[$id] = $data;
			}
		}

		$args['sources'] = $new_sources;

		return $args;
	}

	protected function remove_autocomplete_entries()
	{
		$config = rcmail::get_instance()->config;
		$sources = (array) $config->get('autocomplete_addressbooks', array('sql'));
		$new_sources = array();
		foreach ($sources as $source_id) {
			if (substr($source_id, 0, 8) == 'carddav_') {
				$new_sources = $source_id;
			}
		}
		$config->set('autocomplete_addressbooks', $new_sources);
	}
}
