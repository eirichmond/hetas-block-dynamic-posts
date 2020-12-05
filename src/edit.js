/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody, PanelRow, CheckboxControl, SelectControl, ColorPicker } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { className, attributes, setAttributes } ) {
	
	return (
		<div className={ className }>
			<InspectorControls>
				<PanelBody
					title="HETAS Dynamic Posts"
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label="Toggle me (disabled)"
							checked={attributes.toggle}
							onChange={(newval) => setAttributes({ toggle: newval })}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Number of posts to show"
							value={attributes.postsPerPage}
							options={[
								{label: "1", value: '1'},
								{label: "2", value: '2'},
								{label: "3", value: '3'},
								{label: "4", value: '4'},
								{label: "5", value: '5'},
								{label: "6", value: '6'},
								{label: "7", value: '7'},
								{label: "8", value: '8'},
								{label: "9", value: '9'},
								{label: "10", value: '10'},
							]}
							onChange={(newval) => setAttributes({ postsPerPage: newval })}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
                block="hetas-block-dynamic-posts/hetas-block-dynamic-posts"
                attributes={ {
					toggle: attributes.toggle,
					postsPerPage: attributes.postsPerPage
				} }
            />
		</div>
	);
}
