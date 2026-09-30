/**
 * The contact section, in the editor.
 *
 * The one block drawn by the server here: the form inside it is Contact Form 7's, which
 * only the plugin can render, so the whole section is fetched from its file under includes/ and the
 * words are edited in the sidebar. Everything else on the site is drawn in the canvas.
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Button, Disabled, PanelBody } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

import { Field } from '../shared/controls';

import metadata from './block.json';

export default function Edit( { attributes, setAttributes } ) {
	const {
		title,
		subtitle,
		phones,
		emailLabel,
		email,
		formTitle,
		formText,
		form,
	} = attributes;

	const blockProps = useBlockProps();
	const set = ( name ) => ( value ) => setAttributes( { [ name ]: value } );

	const setPhone = ( index, change ) =>
		setAttributes( {
			phones: phones.map( ( phone, i ) =>
				i === index ? { ...phone, ...change } : phone
			),
		} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Details', 'osous' ) }>
					<Field
						label={ __( 'Heading', 'osous' ) }
						value={ title }
						onChange={ set( 'title' ) }
					/>
					<Field
						label={ __( 'Subheading', 'osous' ) }
						value={ subtitle }
						onChange={ set( 'subtitle' ) }
					/>
					{ phones.map( ( phone, index ) => (
						<div key={ index } style={ { marginBottom: '12px' } }>
							<Field
								/* translators: %d: which phone number, counting from one. */
								label={ __(
									'Phone %d, label',
									'osous'
								).replace( '%d', index + 1 ) }
								value={ phone.label }
								onChange={ ( value ) =>
									setPhone( index, { label: value } )
								}
							/>
							<Field
								label={ __( 'Number', 'osous' ) }
								value={ phone.number }
								onChange={ ( value ) =>
									setPhone( index, { number: value } )
								}
							/>
							<Button
								variant="tertiary"
								isDestructive
								onClick={ () =>
									setAttributes( {
										phones: phones.filter(
											( _, i ) => i !== index
										),
									} )
								}
							>
								{ __( 'Remove this number', 'osous' ) }
							</Button>
						</div>
					) ) }
					<Button
						variant="secondary"
						onClick={ () =>
							setAttributes( {
								phones: [
									...phones,
									{ label: '', number: '' },
								],
							} )
						}
						style={ { marginBottom: '12px' } }
					>
						{ __( 'Add a number', 'osous' ) }
					</Button>
					<Field
						label={ __( 'Email label', 'osous' ) }
						value={ emailLabel }
						onChange={ set( 'emailLabel' ) }
					/>
					<Field
						label={ __( 'Email address', 'osous' ) }
						value={ email }
						onChange={ set( 'email' ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Form panel', 'osous' ) }>
					<Field
						label={ __( 'Heading', 'osous' ) }
						value={ formTitle }
						onChange={ set( 'formTitle' ) }
					/>
					<Field
						label={ __( 'Line under it', 'osous' ) }
						value={ formText }
						onChange={ set( 'formText' ) }
					/>
					<Field
						label={ __( 'Contact form', 'osous' ) }
						help={ __(
							'The fields, the messages and who receives the mail are edited under Contact → Contact Forms. Only which form sits here is edited from this box.',
							'osous'
						) }
						value={ form }
						onChange={ set( 'form' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<Disabled>
					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
						httpMethod="POST"
					/>
				</Disabled>
			</div>
		</>
	);
}
