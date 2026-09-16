/**
 * An item of a pinned list, in the editor.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { OptionalText } from '../shared/controls';

const TITLE = { medium: 't-m-lg', bold: 't-b-lg' };
const TEXT = { normal: 'p', small: 'p-r-sm', plain: '', bold: 'p-b' };

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { title, text, titleStyle, textStyle } = attributes;

	const blockProps = useBlockProps( { className: 'list-item' } );
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Item', 'osous' ) }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Title weight', 'osous' ) }
						value={ TITLE[ titleStyle ] ? titleStyle : 'medium' }
						options={ [
							{ value: 'medium', label: __( 'Medium', 'osous' ) },
							{ value: 'bold', label: __( 'Bold', 'osous' ) },
						] }
						onChange={ set( 'titleStyle' ) }
					/>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ __( 'Text style', 'osous' ) }
						value={
							TEXT[ textStyle ] !== undefined
								? textStyle
								: 'normal'
						}
						options={ [
							{
								value: 'normal',
								label: __( 'Paragraph', 'osous' ),
							},
							{
								value: 'small',
								label: __( 'Small paragraph', 'osous' ),
							},
							{
								value: 'plain',
								label: __( 'Unstyled', 'osous' ),
							},
							{
								value: 'bold',
								label: __( 'Bold paragraph', 'osous' ),
							},
						] }
						onChange={ set( 'textStyle' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<OptionalText
					tagName="div"
					className={ TITLE[ titleStyle ] || 't-m-lg' }
					value={ title }
					onChange={ set( 'title' ) }
					placeholder={ __( 'Title…', 'osous' ) }
					show={ isSelected }
				/>
				<OptionalText
					tagName="p"
					className={ TEXT[ textStyle ] ?? 'p' }
					value={ text }
					onChange={ set( 'text' ) }
					placeholder={ __( 'Text…', 'osous' ) }
					show={ isSelected }
				/>
			</div>
		</>
	);
}
